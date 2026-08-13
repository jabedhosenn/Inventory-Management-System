<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('api_token');
        // If the token is not present, redirect to the login page
        if (! $token){
            return redirect()->route('login');
        }
        // Find the access token using the provided token
        $accessToken = PersonalAccessToken::findToken($token);
        // If the access token is not found, redirect to the login page and forget the api_token cookie
        if (! $accessToken){
            return redirect()->route('login')->withCookie(
                cookie()->forget('api_token')
            );
        }
        // If the access token is found, retrieve the associated user
        $user = $accessToken->tokenable;
        // If the user is not found, redirect to the login page and forget the api_token cookie
        if (! $user){
            return redirect()->route('login')->withCookie(
                cookie()->forget('api_token')
            );
        }
        // Set the authenticated user for the current request
        auth()->setUser($user);
        // Proceed to the next middleware or request handler
        return $next($request);
    }
}
