<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'heure_debut' => $this->heure_debut,
            'heure_fin' => $this->heure_fin,
            'salle' => $this->salle->libelle,
            'module' => $this->planification_cour_par_classe->planification_cour->module->libelle,
            'image' => $this->planification_cour_par_classe->planification_cour->module->photo,
        ];
    }
}
