<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminSystem
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $roles = auth()->user()->roles;
            $adminSystemConfig = config('settings.admin_system');

            foreach ($roles as $role) {
                $nameOfType = $role->type->name;

                if ($nameOfType === $adminSystemConfig) {
                    return $next($request);
                }
            }
        }

        return redirect('/');
    }
}
