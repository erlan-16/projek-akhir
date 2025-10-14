    @extends('layouts.app')

    @section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold text-gray-800">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                Dashboard Bendahara
            </h1>
            <p class="text-muted">Kelola pembayaran kas kelas dan keuangan</p>
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
        <!-- CAROUSEL PEMASUKAN -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-arrow-up me-2"></i>
                        Pemasukan Terbaru
                        @if($incomeTransactions->count() > 0)
                            <span class="badge bg-light text-success ms-2">{{ $incomeTransactions->count() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($incomeTransactions->count() > 0)
                        <div id="incomeCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($incomeTransactions->take(10) as $index => $transaction)
                                <button type="button" data-bs-target="#incomeCarousel" data-bs-slide-to="{{ $index }}" 
                                        class="@if($index == 0) active @endif" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <div class="carousel-inner">
                                @foreach($incomeTransactions->take(10) as $index => $transaction)
                                <div class="carousel-item @if($index == 0) active @endif">
                                    <div class="p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="fw-bold text-success mb-1">
                                                    {{ $transaction->description }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success">Income</span>
                                        </div>

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

                                        <div class="mt-3">
                                            <small class="text-muted">
                                                Dicatat oleh: <strong>{{ $transaction->creator->name }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($incomeTransactions->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#incomeCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#incomeCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">Belum ada pemasukan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- CAROUSEL PENGELUARAN -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-arrow-down me-2"></i>
                        Pengeluaran Terbaru
                        @if($expenseTransactions->count() > 0)
                            <span class="badge bg-light text-danger ms-2">{{ $expenseTransactions->count() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($expenseTransactions->count() > 0)
                        <div id="expenseCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($expenseTransactions->take(10) as $index => $transaction)
                                <button type="button" data-bs-target="#expenseCarousel" data-bs-slide-to="{{ $index }}" 
                                        class="@if($index == 0) active @endif" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <div class="carousel-inner">
                                @foreach($expenseTransactions->take(10) as $index => $transaction)
                                <div class="carousel-item @if($index == 0) active @endif">
                                    <div class="p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="fw-bold text-danger mb-1">
                                                    {{ $transaction->description }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-danger">Expense</span>
                                        </div>

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
                            <button class="carousel-control-prev" type="button" data-bs-target="#expenseCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#expenseCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">Belum ada pengeluaran</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payments dan Form Tambah Transaksi -->
    <div class="row">
        <!-- Pending Payments -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Pembayaran Pending
                        @if($pendingPayments->count() > 0)
                            <span class="badge bg-warning ms-2">{{ $pendingPayments->count() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Nominal</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $payment->user->name }}</div>
                                            @if($payment->description)
                                                <small class="text-muted">{{ $payment->description }}</small>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="text-muted">
                                            {{ $payment->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('payments.approve', $payment) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm me-1" title="Konfirmasi"
                                                        onclick="return confirm('Konfirmasi pembayaran dari {{ $payment->user->name }}?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('payments.reject', $payment) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Tolak"
                                                        onclick="return confirm('Tolak pembayaran dari {{ $payment->user->name }}?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">Tidak ada pembayaran yang pending</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Tambah Transaksi -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-plus text-primary me-2"></i>
                        Tambah Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Ada kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('transactions.store') }}" id="transactionForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">
                                Jenis Transaksi *
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    name="type" 
                                    id="type" 
                                    required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>
                                    Pemasukan
                                </option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>
                                    Pengeluaran
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">
                                Nominal *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" 
                                    class="form-control @error('amount') is-invalid @enderror" 
                                    name="amount" 
                                    id="amount" 
                                    value="{{ old('amount') }}" 
                                    placeholder="0" 
                                    min="1" 
                                    step="1"
                                    required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="amount-preview" class="mt-2"></div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                Deskripsi *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    name="description" 
                                    id="description" 
                                    rows="3" 
                                    placeholder="Masukkan deskripsi..."
                                    required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                Simpan Transaksi
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const amountPreview = document.getElementById('amount-preview');
        const form = document.getElementById('transactionForm');
        const submitBtn = document.getElementById('submitBtn');
        
        amountInput.addEventListener('input', function() {
            const value = this.value;
            if (value && value > 0) {
                const formatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                amountPreview.innerHTML = `<div class="alert alert-info py-2 mb-0 small"><i class="fas fa-info-circle me-1"></i><strong>${formatted}</strong></div>`;
            } else {
                amountPreview.innerHTML = '';
            }
        });
        
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        });
    });
    </script>
    @endpush