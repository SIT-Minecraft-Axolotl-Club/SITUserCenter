<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class RejectAuthOnlyUser
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->isAuthOnly()) {
            return response()->view('errors.auth-only', [
                'user' => $user,
            ], 403);
        }

        return $next($request);
    }
}