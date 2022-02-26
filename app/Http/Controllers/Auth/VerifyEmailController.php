<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->expectsJson() ? parse_json([
                'message' => 'Your account has been verified.',
            ], Response::HTTP_OK) : redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $request->expectsJson() ? parse_json([
            'message' => 'Your account has been verified.',
        ], Response::HTTP_OK) : redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
        );
    }
}
