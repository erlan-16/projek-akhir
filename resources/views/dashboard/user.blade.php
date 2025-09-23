@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-bold text-gray-800">
            <i class="fas fa-user me-2 text-primary"></i>
            Dashboard Siswa
        </h1>
        <p class="text-muted">Kelola pembayaran kas kelas Anda</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-gradient rounded-circle p-3">
                            <i class="fas fa-arrow-up text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Pemasukan</div>
                        <div class="fs-4 fw-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-gradient rounded-circle p-3">
                            <i class="fas fa-arrow-down text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Pengeluaran</div>
                        <div class="fs-4 fw-bold text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-gradient rounded-circle p-3">
                            <i class="fas fa-wallet text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Saldo Akhir</div>
                        <div class="fs-4 fw-bold text-primary">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Payment Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-credit-card text-success me-2"></i>
                    Bayar Kas
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold">Nominal Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   name="amount" id="amount" value="{{ old('amount') }}" 
                                   placeholder="10000" min="1000" required>
                        </div>
                        <div class="form-text">Minimum pembayaran Rp 1.000</div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" id="description" rows="3" 
                                  placeholder="Contoh: Kas bulan Januari">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane me-2"></i>
                        Kirim Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-history text-info me-2"></i>
                    Riwayat Pembayaran Saya
                </h5>
            </div>
            <div class="card-body">
                @if($userPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userPayments as $payment)
                                <tr>
                                    <td class="text-muted">
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($payment->status == 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                Pending
                                            </span>
                                        @elseif($payment->status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $payment->description ?? '-' }}
                                        @if($payment->approved_at)
                                            <br><small class="text-muted">
                                                Dikonfirmasi: {{ $payment->approved_at->format('d M Y H:i') }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">Anda belum melakukan pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Expenses -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-arrow-down text-danger me-2"></i>
                    Riwayat Pengeluaran Kelas
                </h5>
            </div>
            <div class="card-body">
                @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr>
                                    <td class="text-muted">
                                        {{ $expense->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="fw-semibold">{{ $expense->description }}</td>
                                    <td class="fw-bold text-danger">
                                        -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-muted">{{ $expense->creator->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-box text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">Belum ada pengeluaran yang dicatat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection