<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanificationCourRequest;
use App\Models\PlanificationCour;
use App\Models\PlanificationCourParClasse;
use Illuminate\Http\Request;

class PlanificationCourController extends Controller
{
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
        return PlanificationCour::create([
            "professeur_id" => $request->professeur_id,
            "semestre_id" => $request->semestre_id,
            "module_id" => $request->module_id,
        ]);
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
