<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureIsUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() instanceof User && auth()->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'This action is unauthorized.'], 403);
    }
}
