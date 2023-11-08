<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeGetRegistration(Builder $builder, $idIns, $idSession)
    {
        return $builder->where(["inscription_id" => $idIns, "planification_session_cour_id" => $idSession]);
    }
}
