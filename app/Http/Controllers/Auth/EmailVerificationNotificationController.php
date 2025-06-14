<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse | JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // If it’s an API call, return JSON; otherwise, redirect.
            return $request->expectsJson()
                ? response()->json(['message' => 'Already verified.'], 200)
                : redirect()->route('verification.notice');
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }
}
