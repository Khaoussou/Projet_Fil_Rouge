<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function scopeGetPlace(Builder $builder, $salle_id)
    {
        return $builder->where('id', $salle_id);
    }
}
