<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Get the current route name
        $routeName = $request->route()->getName();

        // Example permission check: Check if user has permission for this route
        if (!$user->hasPermission($routeName)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
