<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseCourResource;
use App\Http\Resources\CourParClasseResource;
use App\Http\Resources\CourResource;
use App\Http\Resources\SalleResource;
use App\Http\Resources\SessionResource;
use App\Models\ClasseAnnee;
use App\Models\PlanificationCour;
use App\Models\PlanificationCourParClasse;
use App\Models\PlanificationSessionCour;
use App\Models\Salle;
use App\Traits\Format;
use DateTime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanificationSessionController extends Controller
{
    use Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = SalleResource::collection(Salle::all());
        $sessions = SessionResource::collection(PlanificationSessionCour::all());
        $allSessions = PlanificationSessionCour::all();
        return $this->response(Response::HTTP_ACCEPTED, '', ['session' => $sessions, 'salles' => $salles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = $request->date;
        $hd = $request->hd;
        $hf = $request->hf;
        $salle = $request->salle;
        $classes = $request->courClasses;
        $salleDispo = $this->salleDispo($request);
        $courClasse = [];
        $idCours = 0;
        foreach ($classes as $id) {
            $idCours = PlanificationCourParClasse::getClasseAnne($id)->first()->planification_cour_id;
        }
        $professeur = PlanificationCour::getData($idCours)->first();
        $allCours = ClasseCourResource::collection(PlanificationCourParClasse::getClasse($idCours)->get());
        $diff = strtotime($hf) - strtotime($hd);
        $prof = $this->profDispo($request, $professeur->professeur_id);
        if ($hd < '08:00') {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Désolé mais un cours ne peut pas débuter avant 08h !", []);
        }
        if ($prof['message'] == 'Ce prof est disponible !' && $salleDispo['message'] == 'La salle est disponible !') {
            if ($this->conversionToSecondes(($allCours[0]->nbr_heure_restant . ':00:00')) >= (strtotime($hf) - strtotime($hd))) {
                foreach ($classes as $classe) {
                    $courClasse[] = [
                        "date" => $date,
                        "heure_debut" => $hd,
                        "heure_fin" => $hf,
                        "cour_classe_id" => $classe,
                        "salle_id" => $salle,
                        "professeur_id" => $professeur->professeur_id,
                        "etat" => "En cours"
                    ];
                }
                foreach ($allCours as $cours) {
                    $reste = $this->conversionToSecondes(($cours->nbr_heure_restant . ':00:00')) - $diff;
                    PlanificationCourParClasse::where('planification_cour_id', $idCours)
                        ->update([
                            'nbr_heure_restant' => ($reste / 3600)
                        ]);
                }
                PlanificationSessionCour::insert($courClasse);
                return $this->response(Response::HTTP_ACCEPTED, "Votre séssion a été bien enregistré !", ['session' => $courClasse]);
            } else {
                return $this->response(Response::HTTP_UNAUTHORIZED, "Désolé mais cette séssion ne peut pas etre programmée !", []);
            }
        } elseif ($prof['message'] != 'Ce prof est disponible !') {
            return $prof;
        } else {
            return $salleDispo;
        }
    }

    public function conversionToSecondes($duree)
    {
        list($heures, $minutes, $secondes) = explode(':', $duree);
        return $heures * 3600 + $minutes * 60 + $secondes;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function profDispo(Request $request, $profId)
    {
        $session = PlanificationSessionCour::getSessionProf($request->date, $profId)->first();
        if ($request->date >= now() && strtotime($request->hf) > strtotime($request->hd)) {
            if ($session) {
                if (strtotime($session->heure_debut) == strtotime($request->hd) && strtotime($session->heure_fin) == strtotime($request->hf)) {
                    return $this->response(Response::HTTP_UNAUTHORIZED, "Ce prof a déja une séssion prévue pour cette heure !", []);
                } elseif (strtotime($request->hd) >= strtotime($session->heure_debut) && strtotime($request->hd) < strtotime($session->heure_fin)) {
                    return $this->response(Response::HTTP_UNAUTHORIZED, "Ce prof a déja une séssion prévue pour cette heure !", []);
                } elseif (strtotime($request->hf) >= strtotime($session->heure_debut) && strtotime($request->hf) <= strtotime($session->heure_fin)) {
                    return $this->response(Response::HTTP_UNAUTHORIZED, "Ce prof a déja une séssion prévue pour cette heure !", []);
                }
            }
            return $this->response(Response::HTTP_ACCEPTED, "Ce prof est disponible !", []);
        }
        return $this->response(Response::HTTP_UNAUTHORIZED, "Vos données ne sont pas correctes, veuillez vérifier soit la date ou les heures!", []);
    }

    public function salleDispo(Request $request)
    {
        $effectifs = 0;
        $classeAnneeIds = [];
        $classes = $request->courClasses;
        foreach ($classes as $id) {
            $classeAnneeIds[] = PlanificationCourParClasse::getClasseAnne($id)->first()->classe_annee_id;
        }
        foreach ($classeAnneeIds as $id) {
            $effectifs += ClasseAnnee::getClasse($id)->first()->effectif;
        }
        $session = PlanificationSessionCour::getSalle($request->date, $request->salle)->first();
        $nbrPlace = Salle::getPlace($request->salle)->first()->nbr_de_place;
        if ($nbrPlace < $effectifs) {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Impossible de planifier cette session car il n'y a pas assez de place disponible dans cette salle !", []);
        } elseif ($session) {
            if (strtotime($session->heure_debut) == strtotime($request->hd) && strtotime($session->heure_fin) == strtotime($request->hf)) {
                return $this->response(Response::HTTP_UNAUTHORIZED, "Une séssion est déja prévue dans cette salle pour cette heure !", []);
            } elseif (strtotime($request->hd) >= strtotime($session->heure_debut) && strtotime($request->hd) < strtotime($session->heure_fin)) {
                return $this->response(Response::HTTP_UNAUTHORIZED, "Une séssion est déja prévue dans cette salle pour cette heure !", []);
            } elseif ($request->hf >= strtotime($session->heure_debut) && $request->hf <= strtotime($session->heure_fin)) {
                return $this->response(Response::HTTP_UNAUTHORIZED, "Une séssion est déja prévue dans cette salle pour cette heure !", []);
            }
        }
        return $this->response(Response::HTTP_ACCEPTED, "La salle est disponible !", []);
    }

    public function courClasse($id)
    {
        $allCours = ClasseCourResource::collection(PlanificationCourParClasse::getClasse($id)->get());
        return $this->response(Response::HTTP_ACCEPTED, 'Voici les classes', ['classes' => $allCours]);
    }

    public function getSessions(Request $request)
    {
        $date = $request->date;
        $courClasses = $request->courClasses;
        $hd = $request->hd;
        $hf = $request->hf;
        $sessionIds = [];
        foreach ($courClasses as $id) {
            $sessionIds[] = PlanificationSessionCour::getSesions($date, $hd, $hf, $id)->first()->id;
        }
        return $sessionIds;
    }

    public function sessionDispo(Request $request, $id)
    {
        $allCours = ClasseCourResource::collection(PlanificationCourParClasse::getClasse($id)->get());
        if ($this->conversionToSecondes(($allCours[0]->nbr_heure_restant . ':00:00')) < (strtotime($request->hf) - strtotime($request->hd))) {
            return $this->response(Response::HTTP_UNAUTHORIZED, 'Désolé mais cette séssion ne peut pas etre programmée car il vous reste : ' . $allCours[0]->nbr_heure_restant . 'h', []);
        } else {
            return $this->response(Response::HTTP_UNAUTHORIZED, 'Vous pouvez programmer cette session !', []);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function modify(Request $request)
    {
        $idSessions = $this->getSessions($request);
        $hd = $request->hd;
        $hf = $request->hf;
        $courClasses = $request->courClasses;
        $diff = strtotime($hf) - strtotime($hd);
        $sessions = [];
        foreach ($idSessions as $id) {
            PlanificationSessionCour::where('id', $id)->update(['etat' => 'Terminer']);
            $sessions[] = PlanificationSessionCour::where('id', $id)->first();
        }
        foreach ($courClasses as $id) {
            $courClass = PlanificationCourParClasse::where('id', $id)->first();
            $reste = $this->conversionToSecondes(($courClass->nbr_heure_effectue . ':00:00')) + $diff;
            PlanificationCourParClasse::where('id', $id)->update(['nbr_heure_effectue' => ($reste / 3600)]);
        }
        return $this->response(Response::HTTP_UNAUTHORIZED, "L'etat de votre session a bien été modifié !", ['sessions' => SessionResource::collection($sessions)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
