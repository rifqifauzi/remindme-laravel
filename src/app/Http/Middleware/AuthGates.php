<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \Auth::user();
        $notAuthorizied = false;

        if ($user) {

            $permissions = require(base_path('app/permissions.php'));

            $permissionsArray = [];

            foreach ($permissions as $permissionName => $roleId) {
                $permissionsArray[$permissionName][] = $roleId;
            }

            foreach ($permissionsArray as $name => $roles) {
                foreach ($roles as $role) {
                    Gate::define($name, function ($user) use ($role) {
                        return count(array_intersect($user->roles->pluck('id')->toArray(), $role)) > 0;
                    });
                }
            }

            if ($notAuthorizied) return abort(403);
        }
        return $next($request);
    }
}
