<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
            $classe->claase_annee()->detach();
            self::add($classe);
        });
    }
    protected static function add($classe)
    {
        $classes = request()->classes;
        $classe->claase_annee()->attach($classes);
    }

    public function claase_annee(): BelongsToMany
    {
        return $this->belongsToMany(ClasseAnnee::class, 'planification_cour_par_classes',);
    }
}
