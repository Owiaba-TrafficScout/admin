<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralController extends Controller
{
    public function EmailVerifiedPasswordResetSuccess($admin)
    {
        $isEmail = $admin !== 'pwd-reset' ? true : false;
        $admin = 'admin' === $admin ? true : false;
        return Inertia::render('EmailVerified', ['admin' => $admin, 'isEmail' => $isEmail]);
    }
}
