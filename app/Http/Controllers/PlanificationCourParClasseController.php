<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourParClasseResource;
use App\Models\PlanificationCourParClasse;
use Illuminate\Http\Request;

class PlanificationCourParClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return PlanificationCourParClasse::all();
        return CourParClasseResource::collection(PlanificationCourParClasse::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request->all();
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
