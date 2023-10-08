<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseAnneeResource;
use App\Models\ClasseAnnee;
use App\Traits\Format;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClasseAnneeController extends Controller
{
    use Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classeAnnee = ClasseAnneeResource::collection(ClasseAnnee::all());
        return $this->response(Response::HTTP_ACCEPTED, '', [$classeAnnee]);
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
}
