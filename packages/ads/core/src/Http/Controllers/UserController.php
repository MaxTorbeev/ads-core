<?php

namespace Ads\Core\Http\Controllers;

use Ads\Core\Http\Requests\UserRequest;
use Ads\Core\Services\User\AuthService;
use Ads\Core\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    private AuthService $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->success(
            $this->userService->index($request->all())
        );
    }

    public function show(User $user): JsonResponse
    {
        return response()->success(
            $this->userService->show($user)
        );
    }

    public function store(UserRequest $request): JsonResponse
    {
        return response()->success(
            $this->userService->store($request->validated())
        );
    }

    public function update(User $user, UserRequest $request): JsonResponse
    {
        return response()->success(
            $this->userService->update($user, $request->validated())
        );
    }

    public function destroy(User $user): JsonResponse
    {
        return response()->success(
            $this->userService->delete($user)
        );
    }

    /**
     * Getting info about auth user.
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        return response()->success($this->userService->info());
    }
}
