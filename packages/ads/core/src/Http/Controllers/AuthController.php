<?php

namespace Ads\Core\Http\Controllers;

use Ads\Core\Exceptions\User\UserNotFoundException;
use Ads\Core\Exceptions\User\UserPasswordInvalidException;
use Ads\Core\Http\Requests\AuthRequest;
use Ads\Core\Services\User\AuthService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserPasswordInvalidException
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if ($request->has('login')) {
            $user = User::where('login', $request->login)->first();
        } else if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        } else if ($request->has('phone')) {
            $user = User::where('phone', $request->phone)->first();
        } else {
            throw new UserNotFoundException();
        }

        if (!$user) {
            throw new UserNotFoundException();
        }

        return response()->success(
            $this->service->login($user, $request->password)
        );
    }

    public function logout(): JsonResponse
    {
        return response()->success(
            $this->service->logout()
        );
    }
}
