<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        $statuses = PaymentStatus::all();
        if (auth()->user()->isProjectAdmin()) {
            $payments = Payment::whereIn('project_id', auth()->user()->projects->pluck('id'))->get();
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
        if ($paymentDetails['status']) {
            //Payment was successful
            //update database
            $data = $paymentDetails['data'];

            return redirect()->route('payment.success', ['success' => true]);
        } else {
            return redirect()->route('payment.success', ['success' => false]);
        }
    }
}
