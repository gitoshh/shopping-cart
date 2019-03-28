<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $request;

    /**
     * UserController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Add a new instance of a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewUser(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validate($this->request, [
                'email'     => 'required|email',
                'firstName' => 'required',
                'lastName'  => 'required',
                'password'  => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        $userAttributes = [
            'email'      => $this->request->email,
            'firstName'  => $this->request->firstName,
            'lastName'   => $this->request->lastName,
            'middleName' => $this->request->middleName ?? null,
            'password'   => Hash::make($this->request->password),
        ];
        $user = User::firstOrCreate($userAttributes);
        if ($user) {
            return response()->json($user);
        }
    }
}
