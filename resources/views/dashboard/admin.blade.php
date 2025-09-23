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

    <!-- FORM TAMBAH TRANSAKSI (DIPERBAIKI) -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-plus text-primary me-2"></i>
                    Tambah Transaksi
                </h5>
            </div>
            <div class="card-body">
                <!-- Form Error Display -->
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
                            <i class="fas fa-list me-1"></i>
                            Jenis Transaksi *
                        </label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                name="type" 
                                id="type" 
                                required>
                            <option value="">-- Pilih Jenis Transaksi --</option>
                            <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>
                                <i class="fas fa-arrow-up"></i> Pemasukan
                            </option>
                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>
                                <i class="fas fa-arrow-down"></i> Pengeluaran
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small>Pilih jenis transaksi yang akan dicatat</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold">
                            <i class="fas fa-money-bill me-1"></i>
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
                        <div class="form-text">
                            <small>Masukkan nominal dalam rupiah (minimal Rp 1)</small>
                        </div>
                        <div id="amount-preview" class="mt-2"></div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            <i class="fas fa-edit me-1"></i>
                            Deskripsi *
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  id="description" 
                                  rows="3" 
                                  placeholder="Masukkan deskripsi transaksi..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small>Jelaskan detail transaksi dengan jelas</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            Simpan Transaksi
                        </button>
                        <button type="reset" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-undo me-2"></i>
                            Reset Form
                        </button>
                    </div>
                </form>

                <!-- Quick Add Templates -->
                <div class="mt-3">
                    <small class="text-muted fw-semibold">Template Cepat:</small>
                    <div class="d-flex gap-1 mt-2 flex-wrap">
                        <button type="button" class="btn btn-outline-success btn-sm template-btn" 
                                data-type="expense" 
                                data-desc="Pembelian alat tulis kelas">
                            <i class="fas fa-pen"></i> Alat Tulis
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm template-btn" 
                                data-type="expense" 
                                data-desc="Biaya fotokopi">
                            <i class="fas fa-copy"></i> Fotokopi
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm template-btn" 
                                data-type="expense" 
                                data-desc="Pembelian perlengkapan kebersihan">
                            <i class="fas fa-spray-can"></i> Kebersihan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="fas fa-history text-info me-2"></i>
                    Transaksi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Dibuat Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td class="text-muted">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td>
                                        @if($transaction->type == 'income')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                Pemasukan
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-down me-1"></i>
                                                Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $transaction->description }}</div>
                                        @if($transaction->payment)
                                            <small class="text-muted">
                                                Dari: {{ $transaction->payment->user->name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="fw-bold {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type == 'income' ? '+' : '-' }}
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $transaction->creator->name }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">Belum ada transaksi</p>
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
    const form = document.getElementById('transactionForm');
    const typeSelect = document.getElementById('type');
    const amountInput = document.getElementById('amount');
    const amountPreview = document.getElementById('amount-preview');
    const submitBtn = document.getElementById('submitBtn');
    
    // Format currency preview
    amountInput.addEventListener('input', function() {
        const value = this.value;
        if (value && value > 0) {
            const formatted = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            amountPreview.innerHTML = `<div class="alert alert-info py-2 mb-0 small"><i class="fas fa-info-circle me-1"></i><strong>${formatted}</strong></div>`;
        } else {
            amountPreview.innerHTML = '';
        }
    });
    
    // Change submit button based on transaction type
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        if (type === 'income') {
            submitBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Tambah Pemasukan';
            submitBtn.className = 'btn btn-success';
        } else if (type === 'expense') {
            submitBtn.innerHTML = '<i class="fas fa-minus-circle me-2"></i>Tambah Pengeluaran';
            submitBtn.className = 'btn btn-danger';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Transaksi';
            submitBtn.className = 'btn btn-primary';
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        // Validation
        const type = typeSelect.value;
        const amount = amountInput.value;
        const description = document.getElementById('description').value;
        
        if (!type || !amount || !description) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }
        
        if (amount < 1) {
            e.preventDefault();
            alert('Nominal harus minimal Rp 1!');
            return false;
        }
        
        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        
        // Re-enable after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.disabled = false;
            if (type === 'income') {
                submitBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Tambah Pemasukan';
            } else if (type === 'expense') {
                submitBtn.innerHTML = '<i class="fas fa-minus-circle me-2"></i>Tambah Pengeluaran';
            } else {
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Transaksi';
            }
        }, 10000);
    });
    
    // Quick template buttons
    document.querySelectorAll('.template-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const description = this.dataset.desc;
            
            typeSelect.value = type;
            document.getElementById('description').value = description;
            
            // Trigger change event for styling
            typeSelect.dispatchEvent(new Event('change'));
            
            // Focus on amount input
            amountInput.focus();
        });
    });
    
    // Reset form functionality
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        amountPreview.innerHTML = '';
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Transaksi';
        submitBtn.className = 'btn btn-primary';
    });
});
</script>
@endpush