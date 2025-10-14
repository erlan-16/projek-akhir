<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard($user);
        }
    }

    private function adminDashboard()
    {
        // Pending payments
        $pendingPayments = Payment::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Financial summary
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Income transactions - maksimal 10 untuk carousel
        $incomeTransactions = Transaction::with(['creator', 'payment.user'])
            ->where('type', 'income')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Expense transactions - maksimal 10 untuk carousel
        $expenseTransactions = Transaction::with(['creator', 'payment.user'])
            ->where('type', 'expense')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.admin', compact(
            'pendingPayments',
            'totalIncome',
            'totalExpense', 
            'balance',
            'incomeTransactions',
            'expenseTransactions'
        ));
    }

    private function userDashboard($user)
    {
        // User's payments
        $userPayments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Class financial summary
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Income transactions untuk siswa - maksimal 10
        $incomeTransactions = Transaction::with('creator')
            ->where('type', 'income')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Expense transactions untuk siswa - maksimal 10
        $expenseTransactions = Transaction::with('creator')
            ->where('type', 'expense')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.user', compact(
            'userPayments',
            'totalIncome',
            'totalExpense',
            'balance',
            'incomeTransactions',
            'expenseTransactions'
        ));
    }
}