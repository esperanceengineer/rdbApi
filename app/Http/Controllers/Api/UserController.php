<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ModelException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Domain\UserService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService)
    {
    }


    
    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);
        $users = $this->userService->getQuery($request);

        return UserResource::collection($users->get());
    }

    
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->setUser(new User(), $request);
        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_CREATED);
    }


    
    public function show(User $user): UserResource|JsonResponse
    {
        Gate::authorize('view', $user);

        try {
            $this->throwModelNotFoundException($user);

            return new UserResource($user);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $this->throwModelNotFoundException($user);
            $user = $this->userService->setUser($user, $request, false);

            return (new UserResource($user))->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    
    public function destroy(User $user): Application|\Illuminate\Http\Response|JsonResponse|ResponseFactory
    {
        Gate::authorize('view', $user);
        try {
            $this->throwModelNotFoundException($user);
            $this->userService->deleteUser($user);
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    
    public function lock(User $user): UserResource|JsonResponse
    {
        Gate::authorize('lock', $user);

        try {
            $this->throwModelNotFoundException($user);
            $user = $this->userService->lock($user);
            return new UserResource($user);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }

    
    public function changePassword(Request $request, User $user): JsonResponse
    {
        Gate::authorize('update', $user);
        try {
            $this->throwModelNotFoundException($user);
            $result = $this->userService->changePassword($user, $request);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }

   
    public function resetPassword(User $user): UserResource|JsonResponse
    {
        Gate::authorize('resetPassword', $user);

        try {
            $this->throwModelNotFoundException($user);
            $user = $this->userService->resetPassword($user);
            return new UserResource($user);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ], ($e instanceof ModelException) ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST);
        }
    }
}
