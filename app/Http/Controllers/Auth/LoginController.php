<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        /**  @var \App\Models\User $user */
        $user = $request->user();
        $user->tokens()->whereName('login')->delete();
        $token = $user->createToken('login');

        return response()->json(api_format([
            'token' => $token->plainTextToken
        ]), Response::HTTP_OK);
    }

    /**
     * Destroy an authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(api_format([]), Response::HTTP_OK);
    }
}
