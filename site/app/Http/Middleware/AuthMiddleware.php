<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        
        if(!$token) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => 'Token required.'
            ], 401);
        }
      
        try {
            $tokenBearer = explode(" ", $token);
            if ($tokenBearer[0] === 'Bearer') {
                $credentials = JWT::decode($tokenBearer[1], env('JWT_SECRET'), ['HS256']);
                if ($credentials->iss != 'user-token') {
                    return response()->json([
                        'message' => 'Unauthorized',
                        'error' => 'Token should provide from user.'
                    ], 401);
                } 
            } else {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Token Type Should to be Bearer.'
                ], 401);
            }        
        } catch(ExpiredException $e) {
          
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
          
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }
      
        $user = User::find($credentials->sub);
      
        // Now let's put the user in the request class so that you can grab it from there
        if (!empty($user)) {
            $request->auth = $user;
        } else {
            return response()->json([
                'error' => 'Provided token is invalid.'
            ], 400);
        }

        return $next($request);
    }
}