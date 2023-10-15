<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanificationCour extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($classe) {
            self::add($classe);
        });
        static::updating(function ($classe) {
            $classe->classe_annee()->detach();
            self::add($classe);
        });
    }
    protected static function add($classe)
    {
        $classes = request()->classes;
        $classe->classe_annee()->attach($classes);
    }
    public function classe_annee(): BelongsToMany
    {
        return $this->belongsToMany(ClasseAnnee::class, 'planification_cour_par_classes');
    }
    public function planificationCourParClasse(): HasMany
    {
        return $this->hasMany(PlanificationCourParClasse::class);
    }
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }
    public function scopeGetData(Builder $builder, $id)
    {
        return $builder->where('id', $id);
    }
    public function scopeGetCourByProf(Builder $builder, $id)
    {
        return $builder->where('professeur_id', $id);
    }
}
