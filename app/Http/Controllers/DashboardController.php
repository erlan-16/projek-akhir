<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $pendingPayments = Payment::with('user')->where('status', 'pending')->get();
            $totalIncome = Transaction::where('type', 'income')->sum('amount');
            $totalExpense = Transaction::where('type', 'expense')->sum('amount');
            $balance = $totalIncome - $totalExpense;
            $recentTransactions = Transaction::with(['creator', 'payment.user'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('dashboard.admin', compact('pendingPayments', 'totalIncome', 'totalExpense', 'balance', 'recentTransactions'));
        } else {
            $userPayments = Payment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $totalIncome = Transaction::where('type', 'income')->sum('amount');
            $totalExpense = Transaction::where('type', 'expense')->sum('amount');
            $balance = $totalIncome - $totalExpense;
            $expenses = Transaction::with('creator')
                ->where('type', 'expense')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.user', compact('userPayments', 'totalIncome', 'totalExpense', 'balance', 'expenses'));
        }
    }
}