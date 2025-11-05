<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        } else {
            return $this->userDashboard($user, $request);
        }
    }

    // ================= ADMIN DASHBOARD =================
    private function adminDashboard(Request $request)
    {
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $pendingPayments = Payment::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // BULAN
        $selectedMonth = $request->get('month', '10');
        $monthlyTransactions = $this->getMonthlyAllTransactions($selectedMonth);
        
        $monthPage = $request->get('month_page', 1);
        $monthPerPage = 10;
        $monthTotalPages = ceil($monthlyTransactions->count() / $monthPerPage);
        $monthStart = ($monthPage - 1) * $monthPerPage;
        $monthlyData = $monthlyTransactions->slice($monthStart, $monthPerPage);

        $monthlyIncomeTotal = $monthlyTransactions->where('type', 'income')->sum('amount');
        $monthlyExpenseTotal = $monthlyTransactions->where('type', 'expense')->sum('amount');

        // SEMUA TRANSAKSI (paginate)
        $allTransactions = Transaction::with(['creator', 'payment.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'trans_page');

        // Info tambahan untuk pagination
        $transPage = $allTransactions->currentPage();
        $transTotalPages = $allTransactions->lastPage();
        $totalTransCount = $allTransactions->total();

        return view('dashboard.admin', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'pendingPayments' => $pendingPayments,

            'transData' => $allTransactions,
            'transPage' => $transPage,
            'transTotalPages' => $transTotalPages,
            'totalTransCount' => $totalTransCount,

            'selectedMonth' => $selectedMonth,
            'monthlyData' => $monthlyData,
            'monthPage' => $monthPage,
            'monthTotalPages' => $monthTotalPages,
            'totalMonthlyCount' => $monthlyTransactions->count(),
            'monthlyIncomeTotal' => $monthlyIncomeTotal,
            'monthlyExpenseTotal' => $monthlyExpenseTotal,
        ]);
    }

    // ================= USER DASHBOARD =================
    private function userDashboard($user, Request $request)
    {
        $userPayments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // daftar transaksi (gabungan pemasukan & pengeluaran)
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);

        return view('dashboard.user', [
            'userPayments' => $userPayments,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'transactions' => $transactions,
        ]);
    }

    // ================= AJAX UNTUK USER (Daftar Transaksi) =================
public function paginateUserTransactions(Request $request)
{
    if (!$request->ajax()) {
        abort(404);
    }

    $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);

    return view('partials.user_transactions_table', compact('transactions'))->render();
}

// ================= AJAX UNTUK USER (Riwayat Pembayaran) =================
public function paginateUserPayments(Request $request)
{
    if (!$request->ajax()) {
        abort(404);
    }

    $user = auth()->user();
    $userPayments = Payment::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10, ['*'], 'payment_page');

    return view('partials.user_payments_table', compact('userPayments'))->render();
}

// ================= AJAX UNTUK ADMIN (Transaksi Terbaru) =================
public function paginateAdminTransactions(Request $request)
{
    if (!$request->ajax()) {
        abort(404);
    }

    $allTransactions = Transaction::with(['creator', 'payment.user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10, ['*'], 'trans_page');

    $transPage = $allTransactions->currentPage();
    $transTotalPages = $allTransactions->lastPage();
    $totalTransCount = $allTransactions->total();

    return view('partials.admin_transactions_table', [
        'transData' => $allTransactions,
        'transPage' => $transPage,
        'transTotalPages' => $transTotalPages,
        'totalTransCount' => $totalTransCount,
    ])->render();
}

// ================= AJAX UNTUK ADMIN (Transaksi Bulanan) =================
public function paginateAdminMonthly(Request $request)
{
    if (!$request->ajax()) {
        abort(404);
    }

    $selectedMonth = $request->get('month', '10');
    $monthlyTransactions = $this->getMonthlyAllTransactions($selectedMonth);
    
    $monthPage = $request->get('month_page', 1);
    $monthPerPage = 10;
    $monthTotalPages = ceil($monthlyTransactions->count() / $monthPerPage);
    $monthStart = ($monthPage - 1) * $monthPerPage;
    $monthlyData = $monthlyTransactions->slice($monthStart, $monthPerPage);

    return view('partials.admin_monthly_table', [
        'monthlyData' => $monthlyData,
        'monthPage' => $monthPage,
        'monthTotalPages' => $monthTotalPages,
        'selectedMonth' => $selectedMonth,
    ])->render();
}
// ================= FUNGSI AMBIL TRANSAKSI BULANAN =================
private function getMonthlyAllTransactions($month)
{
    $year = now()->year;

    return Transaction::with(['creator', 'payment.user'])
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->orderBy('created_at', 'desc')
        ->get();
}

}