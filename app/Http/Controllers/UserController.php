<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public $request;

    /**
     * UserController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Add a new instance of a user.
     *
     * @return JsonResponse
     */
    public function createNewUser(): JsonResponse
    {
        try {
            $this->validate($this->request, [
                'email'     => 'required|email|unique:users',
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
