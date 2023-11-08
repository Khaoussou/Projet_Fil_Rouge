<?php

namespace App\Http\Controllers;

use App\Models\Emargement;
use App\Models\Inscription;
use App\Traits\Format;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmargementController extends Controller
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
        $sessionId = $request->idSession;
        $inscriptionId = Inscription::getClasseAnnee($request->idUser)->first()->id;
        $registrationExiste = Emargement::getRegistration($inscriptionId, $sessionId)->first();
        if ($registrationExiste) {
            return $this->response(Response::HTTP_ACCEPTED, 'Vous avez deja émarger pour ce cours !', []);
        } else {
            $emarg = Emargement::create([
                'inscription_id' => $inscriptionId,
                'planification_session_cour_id' => $sessionId
            ]);
            return $this->response(Response::HTTP_ACCEPTED, 'Emargement réussie !', [$emarg]);
        }
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
