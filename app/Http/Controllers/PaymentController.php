<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        $statuses = PaymentStatus::all();
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
        return redirect()->back();
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->back();
    }
}
