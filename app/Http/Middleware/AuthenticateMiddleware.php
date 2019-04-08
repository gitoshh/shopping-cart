<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class AuthenticateMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request     $request
     * @param Closure     $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!isset($request->headers->all()['authorization'])) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Authorization token not provided.',
            ], 401);
        }

        $token = $request->headers->all()['authorization'];
        $token = $token[0];

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $user = User::find($credentials->sub);
            if ($user) {
                // Add the current user to the request
                $this->auth = $user;

                return $next($request);
            }

            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.',
            ], 400);
        }
    }
}
