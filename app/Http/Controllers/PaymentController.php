<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request)
    {
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pembayaran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' berhasil diajukan dan menunggu konfirmasi bendahara.');
    }

    public function approve(Payment $payment)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diproses sebelumnya.');
        }

        $payment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        Transaction::create([
            'type' => 'income',
            'amount' => $payment->amount,
            'description' => 'Pembayaran kas dari ' . $payment->user->name . ($payment->description ? ' - ' . $payment->description : ''),
            'payment_id' => $payment->id,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran dari ' . $payment->user->name . ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' berhasil dikonfirmasi.');
    }

    public function reject(Payment $payment)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diproses sebelumnya.');
        }

        $payment->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran dari ' . $payment->user->name . ' telah ditolak.');
    }
}