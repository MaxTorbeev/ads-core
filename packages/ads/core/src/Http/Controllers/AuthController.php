<?php

namespace Ads\Core\Http\Controllers;

use Ads\Core\Exceptions\User\UserNotFoundException;
use Ads\Core\Exceptions\User\UserPasswordInvalidException;
use Ads\Core\Http\Requests\AuthRequest;
use Ads\Core\Services\User\AuthService;
use Ads\Core\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $service;
    private UserService $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->service = $authService;
        $this->userService = $userService;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserPasswordInvalidException
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $user = $this->userService->userByCredential($request->validated());

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
