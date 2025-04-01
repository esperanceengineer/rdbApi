<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\CenterResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\LocalityResource;
use App\Http\Resources\ProvincyResource;
use App\Services\Domain\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct(private readonly StatisticService $statisticService)
    {
    }

    public function localities(Request $request) {
        $localities = $this->statisticService->getLocalityQuery($request);
        $localities = LocalityResource::collection($localities->get());

        return ApiResponseClass::sendResponse(result: $localities, message: 'Liste des arrondissements', code: 200);

    }

    public function provincies(Request $request) {
        $provinces = $this->statisticService->getProvincyQuery($request);
        $provinces = ProvincyResource::collection($provinces->get());

        return ApiResponseClass::sendResponse(result: $provinces, message: 'Liste des provinces', code: 200);

    }

    public function departments(Request $request) {
        $departments = $this->statisticService->getDepartmentQuery($request);
        $departments = DepartmentResource::collection($departments->get());

        return ApiResponseClass::sendResponse(result: $departments, message: 'Liste des départements', code: 200);

    }

    public function centers(Request $request) {
        $centers = $this->statisticService->getCenterQuery($request);
        $centers = CenterResource::collection($centers->get());

        return ApiResponseClass::sendResponse(result: $centers, message: 'Liste des centres', code: 200);

    }

    public function candidates(Request $request) {
        $users = $this->statisticService->getCandidateQuery($request);
        $candidates = CandidateResource::collection($users->get());

        return ApiResponseClass::sendResponse(result: $candidates, message: 'Liste des candidats', code: 200);

    }
}
