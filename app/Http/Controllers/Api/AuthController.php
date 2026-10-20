<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @api {post} /api/login Bejelentkezés
     * @apiName Login
     * @apiGroup Auth
     * @apiVersion 1.0.0
     *
     * @apiBody {String} email E-mail cím.
     * @apiBody {String} password Jelszó.
     *
     * @apiSuccess {String} token Bearer token.
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 200 OK
     *     { "token": "1|AbC..." }
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Hibás e-mail cím vagy jelszó.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }
}
