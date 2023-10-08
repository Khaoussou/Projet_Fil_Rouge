<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleProfesseur extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeGetProfByModule(Builder $builder, $moduleId)
    {
        return $builder->where('module_id', $moduleId);
    }
    public function module() : BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
    public function professeur() : BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }
}
