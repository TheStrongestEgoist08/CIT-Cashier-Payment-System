<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if ($role === 'both') {
            if (in_array($userRole, ['admin', 'cashier'])) {
                return $next($request);
            }
        }
        else {
            $allowedRoles = explode(',', $role);

            if (in_array($userRole, $allowedRoles)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
