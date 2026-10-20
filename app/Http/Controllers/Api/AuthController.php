<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @apiDefine Auth Hitelesítés
 * Bejelentkezés, regisztráció, token kezelése.
 */

/**
 * @apiDefine Counties Megyék
 * Megyék listázása és kezelése.
 */

/**
 * @apiDefine PostalCodes Irányítószámok
 * Irányítószámok keresése, megye szerinti szűrése és kezelése.
 */

/**
 * @apiDefine TokenAuth
 * @apiHeader {String} Authorization Bearer token a bejelentkezésből: <code>Bearer 1|abc...</code>
 * @apiHeader {String} Accept <code>application/json</code>
 * @apiError (401) Unauthenticated Hiányzó vagy érvénytelen token.
 * @apiErrorExample {json} 401:
 *     HTTP/1.1 401 Unauthorized
 *     { "message": "Unauthenticated." }
 */

/**
 * @apiDefine ValidationError
 * @apiError (422) ValidationError Hibás bemeneti adatok.
 * @apiErrorExample {json} 422:
 *     HTTP/1.1 422 Unprocessable Content
 *     {
 *       "message": "The postal code field is required.",
 *       "errors": { "postal_code": ["The postal code field is required."] }
 *     }
 */

/**
 * @apiDefine NotFound
 * @apiError (404) NotFound A keresett rekord nem létezik.
 * @apiErrorExample {json} 404:
 *     HTTP/1.1 404 Not Found
 *     { "message": "No query results for model [App\\Models\\PostalCode] 9999" }
 */
class AuthController extends Controller
{
    /**
     * @api {post} /api/register Regisztráció
     * @apiName Register
     * @apiGroup Auth
     * @apiVersion 1.0.0
     * @apiDescription Új felhasználó létrehozása; a válaszban azonnal kap egy API tokent.
     *
     * @apiBody {String} name Név.
     * @apiBody {String} email E-mail cím (egyedi).
     * @apiBody {String{8..}} password Jelszó.
     * @apiBody {String} password_confirmation Jelszó újra.
     *
     * @apiSuccess (201) {Object} user A létrehozott felhasználó.
     * @apiSuccess (201) {String} token Bearer token.
     * @apiSuccessExample {json} 201:
     *     HTTP/1.1 201 Created
     *     { "user": { "id": 2, "name": "Teszt Elek", "email": "teszt@example.com" }, "token": "2|Xy..." }
     *
     * @apiUse ValidationError
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($data);

        return response()->json([
            'user' => $user->only('id', 'name', 'email'),
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    /**
     * @api {post} /api/login Bejelentkezés
     * @apiName Login
     * @apiGroup Auth
     * @apiVersion 1.0.0
     * @apiDescription Bejelentkezés e-mail címmel és jelszóval. A kapott tokent
     * az adatmódosító kérésekhez az <code>Authorization: Bearer &lt;token&gt;</code> fejlécben kell elküldeni.
     *
     * @apiBody {String} email E-mail cím.
     * @apiBody {String} password Jelszó.
     *
     * @apiExample {json} Kérés:
     *     { "email": "admin@example.com", "password": "password" }
     *
     * @apiSuccess {Object} user A bejelentkezett felhasználó.
     * @apiSuccess {String} token Bearer token.
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "user": { "id": 1, "name": "Admin", "email": "admin@example.com" }, "token": "1|AbC..." }
     *
     * @apiUse ValidationError
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
            'user' => $user->only('id', 'name', 'email'),
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }

    /**
     * @api {get} /api/user Bejelentkezett felhasználó
     * @apiName CurrentUser
     * @apiGroup Auth
     * @apiVersion 1.0.0
     * @apiUse TokenAuth
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "id": 1, "name": "Admin", "email": "admin@example.com" }
     */
    public function user(Request $request)
    {
        return response()->json($request->user()->only('id', 'name', 'email'));
    }

    /**
     * @api {post} /api/logout Kijelentkezés
     * @apiName Logout
     * @apiGroup Auth
     * @apiVersion 1.0.0
     * @apiDescription Az aktuális token visszavonása (törlése).
     * @apiUse TokenAuth
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "message": "Sikeres kijelentkezés." }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sikeres kijelentkezés.']);
    }
}
