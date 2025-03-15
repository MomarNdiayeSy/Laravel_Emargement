<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, explode('|', implode('|', $roles)))) {
            abort(403, 'Accès non autorisé.');
        }
        return $next($request);
    }
}
