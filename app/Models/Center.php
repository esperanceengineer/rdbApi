<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Center extends Model
{
    /** @use HasFactory<\Database\Factories\CenterFactory> */
    use HasFactory;

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function scopeByLocalityId(Builder $builder, int $locality_id): void
    {
        $builder->where('locality_id', $locality_id);
    }
}
