<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\StudentMail;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
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
        foreach ($data as $d) {
            $classeId = Classe::getClasse($d['classe'])->first()->id;
            $yearId = AnneeScolaire::getYear($d['annee'])->first()->id;
            $classeAnnee = ClasseAnnee::getYearClasse($classeId, $yearId)->first();
            $newEtudiant = [
                "name" => $d['name'],
                "date_naissance" => $d['date_naissance'],
                "lieu_naissance" => $d['lieu_naissance'],
                "email" => $d['email'],
                "username" => $d['username'],
                "password" => $d['password'],
                "telephone" => $d['telephone'],
            ];
            if ($d['numero'] == 0 && $classeAnnee) {
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
        Inscription::insert($inscriptions);
        return $this->response(Response::HTTP_ACCEPTED, 'Inscription réussie !', []);
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
