<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function scopeGetYear(Builder $builder, $year)
    {
        return $builder->where('libelle', $year);
    }
}
