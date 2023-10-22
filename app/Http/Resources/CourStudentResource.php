<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourStudentResource extends JsonResource
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
            'photo' => $this->module->photo,
            'etat' => $this->etat,
            'professeur' => $this->professeur->nom_complet,
            'cours' => ClasseStudentResource::collection($this->planificationCourParClasse)
        ];
    }
}
