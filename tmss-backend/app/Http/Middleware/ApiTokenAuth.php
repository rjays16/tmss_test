<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        
        if (!$token) {
            return response()->json([
                'error' => 'Authorization token not provided'
            ], 401);
        }
        
        // Remove "Bearer " prefix if present
        $token = str_replace('Bearer ', '', $token);
        
        // Find user with this token
        $user = User::where('api_token', $token)->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'Invalid authorization token'
            ], 401);
        }
        
        // Attach user to request
        $request->merge(['auth_user' => $user]);
        
        return $next($request);
    }
}
