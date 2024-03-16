<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() instanceof Admin && auth()->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'This action is unauthorized.'], 403);
    }
}
