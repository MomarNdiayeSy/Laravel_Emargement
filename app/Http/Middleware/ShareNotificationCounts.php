<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Emargement;
use Illuminate\Support\Facades\Auth;

class ShareNotificationCounts
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

            if ($role === 'professeur') {
                $newCoursCount = Cours::where('professeur_id', $user->id)
                    ->whereNull('notified_at')
                    ->where('created_at', '>', $user->last_login_at ?? '1970-01-01')
                    ->count();
                view()->share('newCoursCount', $newCoursCount);
            } elseif ($role === 'admin') {
                $pendingEmargementsCount = Emargement::where('valide_par_admin', false)->count();
                view()->share('pendingEmargementsCount', $pendingEmargementsCount);
            }
        }

        return $next($request);
    }
}
