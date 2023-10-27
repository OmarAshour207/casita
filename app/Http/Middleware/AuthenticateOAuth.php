<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Guards\TokenGuard;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateOAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return \response()->json(['success' => false, 'data' => [], 'message' => 'Unauthenticated']);
        }
        return $next($request);
    }
}
