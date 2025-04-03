<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Data\FileType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Result\StoreResult;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\CenterResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\LocalityResource;
use App\Http\Resources\ProvincyResource;
use App\Http\Resources\ResultResource;
use App\Http\Resources\StatisticProvinceResource;
use App\Http\Resources\StatisticResource;
use App\Models\Result;
use App\Models\Statistic;
use App\Services\Domain\StatisticService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StatisticController extends Controller
{
    public function __construct(private readonly StatisticService $statisticService) {}

    public function localities(Request $request)
    {
        $localities = $this->statisticService->getLocalityQuery($request);
        $localities = LocalityResource::collection($localities->get());

        return ApiResponseClass::sendResponse(result: $localities, message: 'Liste des arrondissements', code: 200);
    }

    public function provincies(Request $request)
    {
        $provinces = $this->statisticService->getProvincyQuery($request);
        $provinces = ProvincyResource::collection($provinces->get());

        return ApiResponseClass::sendResponse(result: $provinces, message: 'Liste des provinces', code: 200);
    }

    public function departments(Request $request)
    {
        $departments = $this->statisticService->getDepartmentQuery($request);
        $departments = DepartmentResource::collection($departments->get());

        return ApiResponseClass::sendResponse(result: $departments, message: 'Liste des départements', code: 200);
    }

    public function centers(Request $request)
    {
        $centers = $this->statisticService->getCenterQuery($request);
        $centers = CenterResource::collection($centers->get());

        return ApiResponseClass::sendResponse(result: $centers, message: 'Liste des centres', code: 200);
    }

    public function candidates(Request $request)
    {
        $users = $this->statisticService->getCandidateQuery($request);
        $candidates = CandidateResource::collection($users->get());

        return ApiResponseClass::sendResponse(result: $candidates, message: 'Liste des candidats', code: 200);
    }

    public function storeResult(StoreResult $request)
    {
        DB::beginTransaction();

        try {
            $absension = $request->input('absension');
            $total_registered = $request->input('total_registered');
            $invalid_bulletin = $request->input('invalid_bulletin');
            $expressed_suffrage = $request->input("expressed_suffrage");
            $user_id = $request->input('user_id') ?? Auth::user()->id;
            $center_id = $request->input('center_id');
            $statistics = $request->input('statistics');
            $office = $request->input('office');

            if (!is_null(Result::where('user_id', $user_id)->first())) {

                return response()->json([
                    'message' => 'Vous avez déjà soumis le resultat de ce bureau de vote',
                    'success' => false
                ], Response::HTTP_FORBIDDEN);
            }

            $result = new Result();
            $result->absension  = $absension;
            $result->total_registered  = $total_registered;
            $result->invalid_bulletin  = $invalid_bulletin;
            $result->expressed_suffrage  = $expressed_suffrage;
            $result->user_id  = $user_id;
            $result->center_id  = $center_id;
            if ($file = $request->file('image')) {
                $result->addSimpleFile($file, FileType::RESULT_FILES);
            }
            $result->save();

            $statistics = is_array($statistics) ? $statistics : json_decode($statistics, true);
            foreach ($statistics as $statistic) {
                $newStatistic = new Statistic();
                $newStatistic->vote = $statistic['vote'];
                $newStatistic->candidate_id = $statistic['candidate_id'];
                $newStatistic->result_id = $result->id;
                $newStatistic->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Le resultat du bureau de vote est bien soumis',
                'success' => true,
                'data' => new ResultResource($result)
            ], Response::HTTP_OK);
        } catch (Exception $ex) {
            DB::rollBack();

            return response()->json([
                'message' => $ex->getMessage(),
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getResults(Request $request)
    {

        $result = [];
        $candidate_id = $request->input('candidate_id', 1);

        //Filtre par province status
        if ($status = $request->input('status')) {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, prov.label as province, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id LEFT JOIN centers cent ON rest.center_id = cent.id LEFT JOIN localities local ON cent.locality_id = local.id LEFT JOIN departments depart ON local.department_id = depart.id LEFT JOIN provincies prov ON prov.id = depart.provincy_id WHERE prov.status = :status AND cand.id =:id GROUP BY fullname, id, image, province ORDER BY percentage DESC", ['status' => $status, 'id' => $candidate_id]);
            $statistics = StatisticProvinceResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats par province', code: 200);
        }
        //Filter par centre de vote id
        else if ($center_id = $request->input('center_id')) {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id WHERE rest.center_id = :id AND cand.id = :candidate_id GROUP BY fullname, id, image ORDER BY percentage DESC", ['id' => $center_id, 'candidate_id' => $candidate_id]);
            $statistics = StatisticResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats', code: 200);
        }

        //Filter par arrondissement id
        else if ($locality_id = $request->input('locality_id')) {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id LEFT JOIN centers cent ON rest.center_id = cent.id WHERE cent.locality_id = :id AND cand.id = :candidate_id GROUP BY fullname, id, image ORDER BY percentage DESC", ['id' => $locality_id, 'candidate_id' => $candidate_id]);
            $statistics = StatisticResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats', code: 200);
        }

        //Filter par commune id
        else if ($department_id = $request->input('department_id')) {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id LEFT JOIN centers cent ON rest.center_id = cent.id LEFT JOIN localities local ON cent.locality_id = local.id WHERE local.department_id = :id AND cand.id = :candidate_id GROUP BY fullname, id, image ORDER BY percentage DESC", ['id' => $department_id, 'candidate_id' => $candidate_id]);
            $statistics = StatisticResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats', code: 200);
        }

        //Filter par province id
        else if ($provincy_id = $request->input('provincy_id')) {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id LEFT JOIN centers cent ON rest.center_id = cent.id LEFT JOIN localities local ON cent.locality_id = local.id LEFT JOIN departments depart ON local.department_id = depart.id WHERE depart.provincy_id = :id AND cand.id = :candidate_id GROUP BY fullname, id, image ORDER BY percentage DESC", ['id' => $provincy_id, 'candidate_id' => $candidate_id]);
            $statistics = StatisticResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats', code: 200);
        } else {
            $result = DB::select("SELECT cand.id, cand.image, cand.fullname as fullname, (SUM(stats.vote) / SUM(rest.expressed_suffrage)) * 100 as percentage FROM candidates cand LEFT JOIN statistics stats ON stats.candidate_id = cand.id LEFT JOIN results rest ON stats.result_id = rest.id LEFT JOIN centers cent ON rest.center_id = cent.id LEFT JOIN localities local ON cent.locality_id = local.id LEFT JOIN departments depart ON local.department_id = depart.id LEFT JOIN provincies prov ON prov.id = depart.provincy_id WHERE prov.all = :id GROUP BY fullname, id, image ORDER BY percentage DESC", ['id' => 1]);
            $statistics = StatisticResource::collection($result);
            return ApiResponseClass::sendResponse(result: $statistics, message: 'Liste des résultats', code: 200);
        }
    }

    public function getResult(int $userId) {

        $result = Result::where('user_id', $userId)->first();
        if (!is_null($result))
        {
            $statistic = new ResultResource($result);
            return ApiResponseClass::sendResponse(result: $statistic, message: 'Le résultat', code: 200);
        }else {
            return ApiResponseClass::sendResponse(result:null,message:"Ce résultat n'exist pas", code:404, success: false);
        }

    }
}
