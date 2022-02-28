<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            return response()->json(api_format([
                'message' => 'Your account has been verified.',
            ]), Response::HTTP_OK);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(api_format([
            'message' => 'Account verification email has been sent.',
        ]), Response::HTTP_OK);
    }
}
