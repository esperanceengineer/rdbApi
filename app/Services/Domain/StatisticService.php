<?php

namespace App\Services\Domain;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StatisticService extends Service
{
    public function getCandidateQuery(Request $request): Builder
    {
        $queryBuilder = Candidate::query();
        return $queryBuilder;
    }

}
