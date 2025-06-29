<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User || !$user->hasPermission($permission)) {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}
