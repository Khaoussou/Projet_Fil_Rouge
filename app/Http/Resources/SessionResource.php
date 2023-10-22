<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'etat' => $this->etat,
            'demande' => $this->demande,
            'prof' => $this->professeur->nom_complet,
            'salle' => $this->salle->libelle,
            'cour_id' => $this->planification_cour_par_classe->planification_cour->id,
            'module' => $this->planification_cour_par_classe->planification_cour->module->libelle,
            'classe' => $this->planification_cour_par_classe->classe_annee->classe->libelle,
            'annee' => $this->planification_cour_par_classe->classe_annee->annee_scolaire->libelle
        ];
    }
}
