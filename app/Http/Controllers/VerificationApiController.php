<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Events\NewUserRegisted;
use Illuminate\Auth\Events\Verified;

class VerificationApiController extends Controller
{

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = \Emrad\User::findOrFail($request->token);
        // if ($request->hasValidSignature()) {
        //     return response()->json('yess');
        // }else {
        //     return response()->json('nooo');
        // }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                                        'status' => 'success',
                                        'message' => 'User email already verified'
                                    ],200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
                                    'status' => 'success',
                                    'message' => 'User verified successfully'
                                ],200);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                                'status' => 'success',
                                'message' => 'User mail already verified'
                            ],200);
        }

        event(new NewUserRegisted($request->user()));
        return response()->json([
                                    'status' => 'success',
                                    'message' => 'User verification mail resent successfully'
                                ],200);
    }
}
