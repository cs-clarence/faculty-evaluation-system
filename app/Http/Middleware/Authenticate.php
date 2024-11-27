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
        $user = $request->user();

        if (!isset($user)) {
            return Response::redirectTo('/');
        }

        if (empty($guards)) {
            return $next($request);
        }

        if ($user->is_archived) {
            return Response::redirectToRoute('account-archived.index');
        }

        $userRoleId = $user->role_id;
        $roleCode = Role::whereId($userRoleId)->first()->code;
        foreach ($guards as $guard) {
            if ($guard === $roleCode) {
                return $next($request);
            }
        }

        return Response::redirectToIntended(RouteServiceProvider::getDashboard($roleCode));
    }
}
