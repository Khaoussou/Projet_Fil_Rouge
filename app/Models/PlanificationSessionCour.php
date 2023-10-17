<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanificationSessionCour extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeGetSessionProf(Builder $builder, $date, $profId)
    {
        return $builder->where([
            'date' => $date,
            'professeur_id' => $profId
        ]);
    }
    public function scopeGetSalle(Builder $builder, $date, $salleId)
    {
        return $builder->where([
            'date' => $date,
            'salle_id' => $salleId
        ]);
    }

    public function scopeGetSesions(Builder $builder, $date, $hd, $hf, $courId)
    {
        return $builder->where([
            'date' => $date,
            'cour_classe_id' => $courId,
            'heure_debut' => $hd,
            'heure_fin' => $hf
        ]);
    }
    public function planification_cour_par_classe(): BelongsTo
    {
        return $this->belongsTo(PlanificationCourParClasse::class, 'cour_classe_id');
    }
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function scopeSessionProf(Builder $builder, $profId)
    {
        return $builder->where('professeur_id', $profId);
    }

    public function scopeGetSession(Builder $builder, $courClasse)
    {
        return $builder->where('cour_classe_id', $courClasse);
    }
}
