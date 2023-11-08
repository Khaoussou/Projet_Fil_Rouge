<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourStudentResource;
use App\Http\Resources\PlanificationCourResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserSessionResource;
use App\Models\AnneeScolaire;
use App\Models\Inscription;
use App\Models\PlanificationCour;
use App\Models\PlanificationCourParClasse;
use App\Models\PlanificationSessionCour;
use App\Traits\Format;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EtudiantController extends Controller
{
    use Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getCourUser($userId)
    {
        $anneeId = Inscription::getClasseAnnee($userId)->first()->classe_annee_id;
        $courClasses = PlanificationCourParClasse::getClasseAnnee($anneeId)->pluck('planification_cour_id');
        if (count($courClasses) != 0) {
            foreach ($courClasses as $courId) {
                $cour[] = PlanificationCour::getData($courId)->first();
            }
            return $this->response(Response::HTTP_ACCEPTED, "Voici l' ensemble des cours qui vous concerne", ['cours' => CourStudentResource::collection($cour)]);
        }
        return $this->response(Response::HTTP_UNAUTHORIZED, "Aucune session disponible pour cet étudiant !", []);
    }
    public function getSessionDay($userId)
    {
        $anneeId = Inscription::getClasseAnnee($userId)->first()->classe_annee_id;
        $courClasseIds = PlanificationCourParClasse::getClasseAnnee($anneeId)->pluck('id');
        $sessions = [];
        $dateNow = Carbon::now();
        $dateNowFormat = $dateNow->format('Y-m-d');
        foreach ($courClasseIds as $id) {
            $sessions[] = PlanificationSessionCour::where(['cour_classe_id' => $id, 'date' => $dateNowFormat])->get();
        }
        $allSessions = collect($sessions)->flatten();
        if (count($allSessions) != 0) {
            $sessionUser = UserSessionResource::collection($allSessions);
            return $this->response(Response::HTTP_ACCEPTED, "Voici l' ensemble de vos sessions du jour !", ['sessions' => $sessionUser]);
        }
        return $this->response(Response::HTTP_UNAUTHORIZED, "Vous n'avez aucune séssion programmée pour aujourd'hui", []);
    }
    public function getSessionCoursUsers($userId, $courId)
    {
        $anneeId = Inscription::getClasseAnnee($userId)->first()->classe_annee_id;
        $idCourClasse = PlanificationCourParClasse::where(['planification_cour_id' => $courId, 'classe_annee_id' => $anneeId])->first()->id;
        $sessions = PlanificationSessionCour::where('cour_classe_id', $idCourClasse)->get();
        $seiossionUser = UserSessionResource::collection($sessions);
        return $this->response(Response::HTTP_ACCEPTED, "Voici l' ensemble des sessions de ce cours", ['sessions' => $seiossionUser]);
    }
}
