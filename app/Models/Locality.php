<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
    /** @use HasFactory<\Database\Factories\LocalityFactory> */
    use HasFactory;

    public function centers(): HasMany
    {
        return $this->hasMany(Center::class);
    }

    public function scopeWithCenters(Builder $builder): void
    {
        $builder->with('centers');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeByDepartmentId(Builder $builder, int $department_id): void
    {
        $builder->where('department_id', $department_id);
    }
}
