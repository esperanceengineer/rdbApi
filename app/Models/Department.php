<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    public function scopeWithLocalities(Builder $builder): void
    {
        $builder->with('localities');
    }

    public function provincy(): BelongsTo
    {
        return $this->belongsTo(Provincy::class);
    }

    public function scopeByProvincyId(Builder $builder, int $provincy_id): void
    {
        $builder->where('provincy_id', $provincy_id);
    }
}
