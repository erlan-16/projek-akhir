<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
       
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya bendahara yang dapat menambah transaksi.');
        }

        
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1|max:100000000',
            'description' => 'required|string|min:5|max:255',
        ], [
            'type.required' => 'Jenis transaksi harus dipilih.',
            'type.in' => 'Jenis transaksi tidak valid.',
            'amount.required' => 'Nominal harus diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal minimal Rp 1.',
            'amount.max' => 'Nominal maksimal Rp 100.000.000.',
            'description.required' => 'Deskripsi harus diisi.',
            'description.min' => 'Deskripsi minimal 5 karakter.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            
            $transaction = Transaction::create([
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => auth()->id(),
            ]);

           
            $type = $request->type === 'income' ? 'Pemasukan' : 'Pengeluaran';
            $message = $type . ' sebesar Rp ' . number_format($transaction->amount, 0, ',', '.') . ' berhasil ditambahkan.';
            
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.');
        }
    }

    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $transactions = Transaction::with(['creator', 'payment.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function destroy(Transaction $transaction)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi.');
        }
    }
}