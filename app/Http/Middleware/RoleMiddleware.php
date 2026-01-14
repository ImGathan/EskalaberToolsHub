<?php

namespace App\Http\Middleware;

use App\Constants\UserConst;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            $constantName = UserConst::class."::$role";
            if (defined($constantName)) {
                $roleValue = constant($constantName);
                if ($request->user()->access_type === $roleValue) {
                    return $next($request);
                }
            }
        }

        abort(403);
    }
}
