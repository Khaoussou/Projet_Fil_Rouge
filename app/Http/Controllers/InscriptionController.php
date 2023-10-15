<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanificationCourResource;
use App\Http\Resources\UserResource;
use App\Mail\StudentMail;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
use App\Models\PlanificationCour;
use App\Models\User;
use App\Traits\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class InscriptionController extends Controller
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
        $data = $request->all();
        $subject = 'Notification de votre mot de passe !';
        $inscriptions = [];
        $classeNonDispo = [];
        foreach ($data as $d) {
            $classe = Classe::getClasse($d['classe'])->first();
            $newEtudiant = [
                "name" => $d['name'],
                "date_naissance" => $d['date_naissance'],
                "lieu_naissance" => $d['lieu_naissance'],
                "email" => $d['email'],
                "username" => $d['username'],
                "password" => $d['password'],
                "telephone" => $d['telephone'],
            ];
            $year = AnneeScolaire::getYear($d['annee'])->first();
            if ($classe && $year) {
                $classeAnnee = ClasseAnnee::getYearClasse($classe->id, $year->id)->first();
                if ($classeAnnee) {
                    if ($d['numero'] == 0) {
                        $user = User::create($newEtudiant);
                        $inscriptions[] = [
                            'classe_annee_id' => $classeAnnee->id,
                            'user_id' => $user->id
                        ];
                        $message = $d['password'];
                        Mail::to($user->email)->send(new StudentMail($user, $message, $subject));
                    } else {
                        $user = User::getUser($d['email'])->first();
                        $inscriptions[] = [
                            'classe_annee_id' => $classeAnnee->id,
                            'user_id' => $user->id
                        ];
                    }
                }
            } else {
                $classeNonDispo[] = $d;
            }
        }
        $inscrits = Inscription::insert($inscriptions);
        if (count($classeNonDispo) == 0) {
            return $this->response(Response::HTTP_ACCEPTED, 'Tous les éléves sont inscrits !', [$inscrits]);
        }
        return $this->response(Response::HTTP_UNAUTHORIZED, "Voici les éléves non inscrits :", [$classeNonDispo]);
    }

    public function updatePassWord(Request $request)
    {
        $user = User::getUser($request->username)->first();
        if ($user) {
            if ($request->password == $request->confirmPassword) {
                $new = User::where('id', $user->id)->update(['password' => Hash::make($request->password)]);
                $message = $request->password;
                $subject = 'Félicitation votre modification a bien réussie !';
                Mail::to($user->email)->send(new StudentMail($user, $message, $subject));
                return $this->response(Response::HTTP_ACCEPTED, 'Votre modification a bien réussie veuillez consulter votre boite mail !', []);
            }
            return $this->response(Response::HTTP_UNAUTHORIZED, 'Les mots de passe ne correspondent pas !', []);
        }
        return $this->response(Response::HTTP_ACCEPTED, "Cet utilisateur n'existe pas !", []);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
