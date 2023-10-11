<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
use App\Models\User;
use App\Traits\Format;
use Illuminate\Http\Request;
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
                "telephone" => $d['telephone'],
            ];
            if ($d['numero'] == 0 && $classeAnnee) {
                $user = User::create($newEtudiant);
                $inscriptions[] = [
                    'classe_annee_id' => $classeAnnee->id,
                    'user_id' => $user->id
                ];
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
