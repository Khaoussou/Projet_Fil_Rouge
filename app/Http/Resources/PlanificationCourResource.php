<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanificationCourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cour_id' => $this->id,
            'semestre' => $this->semestre->libelle,
            'module' => $this->module->libelle,
            'etat' => $this->etat,
            'professeur' => $this->professeur->nom_complet,
            'cours' => CourParClasseResource::collection($this->planificationCourParClasse)
        ];
    }
}
