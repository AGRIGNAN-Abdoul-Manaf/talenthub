<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if ($request->user() && $request->user()->is_banned) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu par un administrateur.'
            ]);
        }

        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            // Redirection ou erreur 403 si le rôle ne correspond pas
            abort(403, 'Action non autorisée.');
        }

        return $next($request);
    }
}