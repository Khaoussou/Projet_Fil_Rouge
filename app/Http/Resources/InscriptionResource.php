<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->user->name,
            "date_naissance" => $this->user->date_naissance,
            "lieu_naissance" => $this->user->lieu_naissance,
            "telephone" => $this->user->telephone,
            "classe" => $this->classe_annee->classe->libelle
        ];
    }
}
