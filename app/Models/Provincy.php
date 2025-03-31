<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Provincy extends Model
{
    /** @use HasFactory<\Database\Factories\ProvincyFactory> */
    use HasFactory;

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function scopeWithDepartments(Builder $builder): void
    {
        $builder->with('departments');
    }
    
    public function scopeByStatus(Builder $builder, string $status): void
    {
        $builder->where('status', $status);
    }


}
