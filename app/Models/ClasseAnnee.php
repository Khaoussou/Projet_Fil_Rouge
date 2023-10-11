<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasseAnnee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function scopeGetClasse(Builder $builder ,$classeAnnee)
    {
        return $builder->where('id', $classeAnnee);
    }

    public function scopeGetYearClasse(Builder $builder , $classId, $yearId)
    {
        return $builder->where(['classe_id' => $classId, 'annee_scolaire_id' => $yearId]);
    }
}
