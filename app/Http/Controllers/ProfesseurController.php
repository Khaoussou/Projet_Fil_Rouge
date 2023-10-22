<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanificationCourResource;
use App\Http\Resources\SessionResource;
use App\Mail\ProfMail;
use App\Models\PlanificationCour;
use App\Models\PlanificationSessionCour;
use App\Models\Professeur;
use App\Models\User;
use App\Traits\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ProfesseurController extends Controller
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
        $prof = [
            'nom_complet' => $request->nom,
            'grade' => $request->grade,
            'specialite' => $request->specialite,
            'annee_scolaire_id' => $request->annee_scolaire
        ];
        $newProf = Professeur::create($prof);
        User::create([
            'name' => $newProf->nom_complet,
            'email' => $request->email,
            'professeur_id' => $newProf->id,
            'username' => $request->username,
            'telephone' => $request->telephone,
            'password' => $request->password,
            'role' => 'Prof'
        ]);
        return $this->response(Response::HTTP_ACCEPTED, 'Insertion réussie !', ['prof' => $newProf]);
    }

    public function courProf($idProf)
    {
        $courProf = PlanificationCour::getCourByProf($idProf)->get();
        if (count($courProf) != 0) {
            return $this->response(Response::HTTP_ACCEPTED, 'Voici les cours de ce prof', ['prof' => PlanificationCourResource::collection($courProf)]);
        }
        return $this->response(Response::HTTP_ACCEPTED, "Aucun cours n'est programmé pour ce prof", []);
    }

    public function sessionProf($idProf)
    {
        $sessionProf = PlanificationSessionCour::sessionProf($idProf)->get();
        if (count($sessionProf) != 0) {
            return $this->response(Response::HTTP_ACCEPTED, 'Voici les cours de ce prof', ['sessions' => SessionResource::collection($sessionProf)]);
        }
        return $this->response(Response::HTTP_ACCEPTED, "Vous n'avez aucune session programmée pour ce cours", []);
    }

    public function demande(Request $request)
    {
        $prof = User::getUserById($request->id)->first();
        $user = User::getUser('khaoussoudiallo7@gmail.com')->first();
        $message = $request->message;
        $subject = 'Notification de demande';
        PlanificationSessionCour::where([
            "date" => $request->date,
            "heure_debut" => $request->hd,
            "heure_fin" => $request->hf,
        ])->update(['demande' => '1']);
        Mail::to('khaoussoudiallo7@gmail.com')->send(new ProfMail($user, $prof->email, $prof, $message, $subject));
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
}
