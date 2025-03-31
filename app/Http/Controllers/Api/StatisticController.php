<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateResource;
use App\Services\Domain\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct(private readonly StatisticService $statisticService)
    {
    }

    public function candidates(Request $request) {
        $users = $this->statisticService->getCandidateQuery($request);
        $candidates = CandidateResource::collection($users->get());

        return ApiResponseClass::sendResponse(result: $candidates, message: 'Liste des candidats', code: 200);

    }
}
