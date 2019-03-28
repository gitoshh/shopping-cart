<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $request;

    /**
     * AuthController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Authenticates user given a payload with email and password.
     *
     * @throws Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(): \Illuminate\Http\JsonResponse
    {
        // Validate request payload
        try {
            $this->validate($this->request, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // If no user is found return a bad request
            return response()->json([
                'error' => 'Email does not exist.',
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user),
            ]);
        }

        // If Hash check fails return an unauthorised response
        return response()->json([
            'error' => 'Email or password is wrong.',
        ], 401);
    }

    /**
     * Creates a JWT instance given a payload.
     *
     * @param User $user
     *
     * @return string
     */
    protected function jwt(User $user): string
    {
        $payload = [
            'iss' => 'lumen-jwt',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60,
        ];

        // Encode the payload with the secret key
        return JWT::encode($payload, env('JWT_SECRET'));
    }
}
