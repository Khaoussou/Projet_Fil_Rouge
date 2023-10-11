<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function scopeGetClasse(Builder $builder, $libelle)
    {
        return $builder->where('libelle', $libelle);
    }
}
