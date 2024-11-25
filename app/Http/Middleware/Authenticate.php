<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, \Closure $next, ...$guards)
    {
        if (empty($guards)) {
            return $next($request);
        }

        $userRoleId = $request->user()->role_id;
        $roleCode = Role::whereId($userRoleId)->first()->code;
        foreach ($guards as $guard) {
            if ($guard === $roleCode) {
                return $next($request);
            }
        }

        return Response::redirectTo(RouteServiceProvider::getDashboard($userRoleId));
    }
}
