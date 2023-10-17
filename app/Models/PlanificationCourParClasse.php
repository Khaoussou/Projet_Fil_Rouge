<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanificationCourParClasse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classe_annee(): BelongsTo
    {
        return $this->belongsTo(ClasseAnnee::class);
    }

    public function planification_cour(): BelongsTo
    {
        return $this->belongsTo(PlanificationCour::class);
    }

    public function cour_classe(): HasMany
    {
        return $this->hasMany(PlanificationSessionCour::class, 'cour_classe_id');
    }

    public function scopeGetClasseAnne(Builder $builder, $id)
    {
        return $builder->where('id', $id);
    }

    public function scopeGetClasse(Builder $builder, $courId)
    {
        return $builder->where("planification_cour_id", $courId);
    }
}
