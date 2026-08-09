<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403);
        }

        $allowed = collect($roles)
            ->map(fn (string $role) => UserRole::from($role))
            ->all();

        if (! $user->isRole(...$allowed)) {
            abort(403, 'You are not allowed to access this area.');
        }

        return $next($request);
    }
}
