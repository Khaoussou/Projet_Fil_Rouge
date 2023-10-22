<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeGetUser(Builder $builder, $classeAnneeId)
    {
        return $builder->where('classe_annee_id', $classeAnneeId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classe_annee(): BelongsTo
    {
        return $this->belongsTo(ClasseAnnee::class);
    }

    public function scopeGetClasseAnnee(Builder $builder, $userId)
    {
        return $builder->where('user_id', $userId);
    }
}
