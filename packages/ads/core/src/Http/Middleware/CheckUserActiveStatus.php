<?php

namespace Ads\Core\Http\Middleware;

use Ads\Core\Services\User\AuthService;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class CheckUserActiveStatus
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $this->authService->user();

        if ($user instanceof Authenticatable && $user->is_active) {
            return $next($request);
        }

        throw new AuthorizationException();
    }
}
