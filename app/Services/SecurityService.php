<?php

namespace App\Services;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecurityService
{
    public function loginApi(LoginUserRequest $request): JsonResponse
    {
        $user = User::where('username', $request->getUsername())->first();
        if (!$user || !Hash::check($request->getPassword(), $user->password)) {

            $key = 'login:'.$request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)){
                $seconds = RateLimiter::availableIn($key);

                return response()->json([
                    'success' => false,
                    'message' => 'Trop de tentatives, réessayez dans '.$seconds. ' secondes',
                ], Response::HTTP_BAD_REQUEST);
            }
            RateLimiter::increment($key);

            return response()->json([
                'success' => false,
                'message' => 'Échec de connexion, vérifiez vos données et envoyez à nouveau',
            ], Response::HTTP_BAD_REQUEST);
        }

        $token = $user->createToken(
            $user->username.'_token',
            ['*'],
            now()->addDays(2)
        )->plainTextToken;

        $success = true;
        $message = 'Connexion réussie';

        if ($user->is_locked) {
            $success = false;
            $message = "Votre compte a été bloqué, rapprochez-vous de l'administrateur!";
        }

        return response()->json([
            'message' => $message,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user)
            ]
        ], $success ? Response::HTTP_OK : Response::HTTP_FORBIDDEN);
    }

    public function logoutApi(Request $request): JsonResponse
    {
        //currentAccessToken
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Utilisateur déconnecté.',
        ], Response::HTTP_OK);
    }
}
