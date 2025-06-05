<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Unicodeveloper\Paystack\Facades\Paystack;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $subscriptionPlans = SubscriptionPlan::all();
        return Inertia::render('Auth/Register', [
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'org_name' => 'required|string|max:255',
            'org_email' => 'required|string|lowercase|email|max:255|unique:tenants,email',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id' => 'required|exists:subscription_plans,id',
            'amount' => 'required|numeric|min:1',
        ]);

        //round ammount to 2 decimal places
        $amount = intval($attributes['amount'] * 100);


        // Store validated data in the session
        session(['user_registration_data' => $attributes]);


        // Generate the payment URL using the Paystack route and redirect to it
        $data = [
            "amount" => $amount, // Paystack expects the amount in pesewas
            "reference" => Paystack::genTranxRef(),
            "email" => $attributes['email'],
            "currency" => "GHS",
            "orderID" => 23456,
        ];

        try {
            $authorizationUrl = Paystack::getAuthorizationUrl($data)->url;
            // return redirect()->away($authorizationUrl); // Properly handle the redirect using Inertia
            return Inertia::location($authorizationUrl); // Properly handle the redirect using Inertia
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Could not initiate Paystack payment. Please try again.']);
        }
    }
}
