<?php

namespace App\Services\Domain;

use App\Models\Candidate;
use App\Models\Center;
use App\Models\Department;
use App\Models\Locality;
use App\Models\Provincy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StatisticService extends Service
{
    public function getCandidateQuery(Request $request): Builder
    {
        $queryBuilder = Candidate::query();

        return $queryBuilder;
    }

    public function getProvincyQuery(Request $request): Builder
    {
        $queryBuilder = Provincy::query();
        $queryBuilder->when(
            $request->has('status'),
            fn(Builder $query) => $query->byStatus($request->input('status'))
        );

        $queryBuilder->when(
            $request->has('departments'),
            fn(Builder $query) => $query->withDepartments()
        );

        return $queryBuilder;
    }

    public function getDepartmentQuery(Request $request): Builder
    {
        $queryBuilder = Department::query();
        $queryBuilder->when(
            $request->has('localities'),
            fn(Builder $query) => $query->withLocalities()
        );

        $queryBuilder->when(
            $request->has('provincy_id'),
            fn(Builder $query) => $query->byProvincyId($request->input('provincy_id'))
        );

        return $queryBuilder;
    }

    public function getLocalityQuery(Request $request): Builder
    {
        $queryBuilder = Locality::query();
        $queryBuilder->when(
            $request->has('centers'),
            fn(Builder $query) => $query->withCenters()
        );

        $queryBuilder->when(
            $request->has('department_id'),
            fn(Builder $query) => $query->byDepartmentId($request->input('department_id'))
        );
        return $queryBuilder;
    }

    public function getCenterQuery(Request $request): Builder
    {
        $queryBuilder = Center::query();
        $queryBuilder->when(
            $request->has('locality_id'),
            fn(Builder $query) => $query->byLocalityId($request->input('locality_id'))
        );
        return $queryBuilder;
    }

}
