<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourParClasseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "cours_classe_id" => $this->id,
            "classe_annee_id" => $this->classe_annee->id,
            "classe" => $this->classe_annee->classe->libelle,
            "annee" => $this->classe_annee->annee_scolaire->libelle,
            "nbr_heure" => $this->nbr_heure,
            "nbr_heure_effectue" => $this->nbr_heure_effectue,
        ];
    }
}
