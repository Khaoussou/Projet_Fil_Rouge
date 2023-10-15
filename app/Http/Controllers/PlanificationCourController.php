<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use App\Traits\Format;
use App\Models\Professeur;
use App\Models\ClasseAnnee;
use Illuminate\Http\Request;
use App\Models\ModuleProfesseur;
use App\Models\PlanificationCour;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\ModuleProfResource;
use App\Http\Resources\ProfesseurResource;
use App\Models\PlanificationCourParClasse;
use App\Http\Resources\ClasseAnneeResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\PlanificationCourRequest;
use App\Http\Resources\InscriptionResource;
use App\Http\Resources\PlanificationCourResource;
use App\Models\Inscription;

class PlanificationCourController extends Controller
{
    use Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cours = PlanificationCourResource::collection(PlanificationCour::all());
        $modules = ModuleResource::collection(Module::all());
        $professeur = ProfesseurResource::collection(Professeur::all());
        $classeAnnee = ClasseAnneeResource::collection(ClasseAnnee::all());
        return $this->response(Response::HTTP_ACCEPTED, '', ['cours' => $cours, 'profs' => $professeur, 'modules' => $modules, 'classes' => $classeAnnee]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cours = PlanificationCour::create([
            "professeur_id" => $request->professeur_id,
            "semestre_id" => $request->semestre_id,
            "module_id" => $request->module_id,
        ]);
        return $this->response(Response::HTTP_ACCEPTED, 'Planification réussie !', ['cours' => new PlanificationCourResource($cours)]);
    }

    public function getProf($moduleId)
    {
        $profModule = ModuleProfesseur::getProfByModule($moduleId)->first();
        if ($profModule) {
            return $this->response(Response::HTTP_ACCEPTED, 'Voici les profs qui enseignent ce module !', ['prof' => new ModuleProfResource($profModule)]);
        }
        return $this->response(Response::HTTP_ACCEPTED, "Aucun prof n'enseigne ce module", []);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cours = PlanificationCour::where('id', $id)->first();
        return $this->response(Response::HTTP_ACCEPTED, "Voici votre cours", ['cours' => new PlanificationCourResource($cours)]);
    }

    public function getStudent($courId)
    {
        $tabInscriptions = [];
        $inscriptions = [];
        $classeAnnees = PlanificationCourParClasse::getClasse($courId)->pluck('classe_annee_id');
        foreach ($classeAnnees as $annee) {
            $tabInscriptions[] = Inscription::getUser($annee)->get();
        }
        foreach ($tabInscriptions as $inscris) {
            foreach ($inscris as $inscription) {
                $inscriptions[] = $inscription;
            }
        }
        $users = InscriptionResource::collection($inscriptions);
        return $this->response(Response::HTTP_ACCEPTED, "Voici les éléves faisant ce cours : ", ['users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        PlanificationCour::where("id", $id)->update(['etat' => $request->etat]);
        $cours = PlanificationCour::where("id", $id)->first();
        return $this->response(Response::HTTP_ACCEPTED, "Le changement d'etat a bien réussi !", ['cours' => new PlanificationCourResource($cours)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
