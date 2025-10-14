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
                <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold">Nominal Pembayaran *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   name="amount" 
                                   id="amount" 
                                   value="{{ old('amount') }}" 
                                   placeholder="0" 
                                   min="1000" 
                                   max="1000000"
                                   required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="amountPreview" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  id="description" 
                                  rows="3" 
                                  placeholder="Contoh: Kas bulan Januari">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>
                            Kirim Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CAROUSEL PEMASUKAN -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success text-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-arrow-up me-2"></i>
                    Pemasukan Kelas
                    @if($incomeTransactions->count() > 0)
                        <span class="badge bg-light text-success ms-2">{{ $incomeTransactions->count() }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                @if($incomeTransactions->count() > 0)
                    <div id="incomeCarouselUser" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($incomeTransactions->take(10) as $index => $transaction)
                            <button type="button" data-bs-target="#incomeCarouselUser" data-bs-slide-to="{{ $index }}" 
                                    class="@if($index == 0) active @endif" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($incomeTransactions->take(10) as $index => $transaction)
                            <div class="carousel-item @if($index == 0) active @endif">
                                <div class="p-4">
                                    <h6 class="fw-bold text-success mb-2">
                                        {{ $transaction->description }}
                                    </h6>
                                    <small class="text-muted d-block mb-3">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </small>

                                    <div class="border-top pt-3">
                                        <div class="text-muted small mb-2">Nominal</div>
                                        <div class="fs-5 fw-bold text-success">
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    @if($transaction->payment)
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            Dari: <strong>{{ $transaction->payment->user->name }}</strong>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($incomeTransactions->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#incomeCarouselUser" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#incomeCarouselUser" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted mb-3" style="font-size: 2rem;"></i>
                        <p class="text-muted small">Belum ada pemasukan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- CAROUSEL PENGELUARAN -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-arrow-down me-2"></i>
                    Pengeluaran Kelas
                    @if($expenseTransactions->count() > 0)
                        <span class="badge bg-light text-danger ms-2">{{ $expenseTransactions->count() }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                @if($expenseTransactions->count() > 0)
                    <div id="expenseCarouselUser" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($expenseTransactions->take(10) as $index => $transaction)
                            <button type="button" data-bs-target="#expenseCarouselUser" data-bs-slide-to="{{ $index }}" 
                                    class="@if($index == 0) active @endif" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($expenseTransactions->take(10) as $index => $transaction)
                            <div class="carousel-item @if($index == 0) active @endif">
                                <div class="p-4">
                                    <h6 class="fw-bold text-danger mb-2">
                                        {{ $transaction->description }}
                                    </h6>
                                    <small class="text-muted d-block mb-3">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </small>

                                    <div class="border-top pt-3">
                                        <div class="text-muted small mb-2">Nominal</div>
                                        <div class="fs-5 fw-bold text-danger">
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <small class="text-muted">
                                            Dicatat oleh: <strong>{{ $transaction->creator->name }}</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($expenseTransactions->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#expenseCarouselUser" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#expenseCarouselUser" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted mb-3" style="font-size: 2rem;"></i>
                        <p class="text-muted small">Belum ada pengeluaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment History -->
<div class="row">
    <div class="col-12">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const amountPreview = document.getElementById('amountPreview');
    
    amountInput.addEventListener('input', function() {
        const value = this.value;
        if (value && value > 0) {
            const formatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            amountPreview.innerHTML = `<div class="alert alert-success py-2 mb-0 small"><i class="fas fa-check me-1"></i><strong>${formatted}</strong></div>`;
        } else {
            amountPreview.innerHTML = '';
        }
    });
});
</script>
@endpush