<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\SubscriptionStatus;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = [];
        $statuses = PaymentStatus::all();
        $tenant = Tenant::find(session('tenant_id'));
        if (auth()->user()->isAdminInTenant()) {
            $payments = $tenant->payments;
        } else {
            $payments = Payment::where('project_id', auth()->user()->adminProjects->pluck('id'))->get();
        }
        return Inertia::render('Payments', [
            'payments' => $payments,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->validate([
            'payment_status_id' => 'required|exists:payment_statuses,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]));
        return redirect()->back()->with('success', 'Payment updated.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted.');
    }


    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData(); //return entire payment data
        if ($paymentDetails['status'] == 'success') {
            //Payment was successful
            //update database
            $data = $paymentDetails['data'];

            //Retrieve the stored user dat from the session
            $user_data = session('user_registration_data');

            if (!$user_data) {
                return redirect()->route('register')->withErrors(['error' => 'User registration data not found.']);
            }

            //create tenant
            $tenant = Tenant::create([
                'name' => $user_data['org_name'],
                'email' => $user_data['org_email'],
            ]);

            //create user
            $user = User::create([
                'name' => $user_data['name'],
                'email' => $user_data['email'],
                'password' => Hash::make($user_data['password']),
            ]);

            //create subscription
            $subscription = $tenant->subscriptions()->create([
                'plan_id' => $user_data['plan_id'],
                'trial_ends_at' => null,
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'status_id' => SubscriptionStatus::where('name', 'active')->first()->id,
            ]);

            //associate user to tenant

            $tenant->users()->syncWithoutDetaching([$user->id => ['tenant_role_id' => config('constants.tenant_roles.admin')]]);

            //Automatically log in the user

            event(new Registered($user));

            Auth::login($user);
            // Clear the session data
            session()->forget('user_registration_data');
            // Redirect to the intended page or dashboard
            return redirect()->route('tenant.select');
            // return redirect()->route('dashboard')->with('success', 'Registration and payment successful.');
        } else {
            return redirect()->route('payment.success', ['success' => false]);
        }
    }
}
