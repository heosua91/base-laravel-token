<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->expectsJson() ? parse_json([
                'message' => 'Your account has been verified.',
            ], Response::HTTP_OK) : redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return parse_json([
            'message' => 'Account verification email has been sent.',
        ], Response::HTTP_OK);
    }
}
