@extends('layouts.app')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-bold"><i class="fas fa-user me-2"></i>Dashboard Siswa</h1>
        <p class="text-muted">Kelola pembayaran kas kelas Anda</p>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                    <i class="fas fa-arrow-up text-white fs-5"></i>
                </div>
                <div class="ms-3">
                    <div class="text-muted small">Total Pemasukan</div>
                    <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="bg-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                    <i class="fas fa-arrow-down text-white fs-5"></i>
                </div>
                <div class="ms-3">
                    <div class="text-muted small">Total Pengeluaran</div>
                    <div class="fs-5 fw-bold text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                    <i class="fas fa-wallet text-white fs-5"></i>
                </div>
                <div class="ms-3">
                    <div class="text-muted small">Saldo Akhir</div>
                    <div class="fs-5 fw-bold text-primary">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FORM BAYAR + DAFTAR TRANSAKSI -->
<div class="row mb-4">
    <!-- FORM BAYAR -->
    <div class="col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card text-success me-2"></i>Bayar Kas</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error!</strong>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" class="form-control" name="amount" placeholder="0" min="1000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Contoh: Kas minggu ini" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR TRANSAKSI (PEMASUKAN + PENGELUARAN) -->
    <div class="col-lg-8 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-exchange-alt me-2"></i>Daftar Transaksi</h5>
            </div>
            <div id="transactions-container" class="card-body p-0">
                @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $t)
                                <tr>
                                    <td>{{ $t->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($t->type === 'income')
                                            <span class="badge bg-success">Pemasukan</span>
                                        @else
                                            <span class="badge bg-danger">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td>{{ $t->description }}</td>
                                    <td class="text-end fw-bold {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-2 border-top">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-inbox mb-2" style="font-size: 2rem;"></i>
                        <p>Belum ada data transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- RIWAYAT PEMBAYARAN SAYA -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-history text-info me-2"></i>Riwayat Pembayaran Saya</h5>
            </div>
            <div id="payments-history-container" class="card-body">
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
                                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($payment->status == 'pending')
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                        @elseif($payment->status == 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->description ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="fas fa-receipt mb-2" style="font-size: 2rem;"></i>
                        <p>Anda belum melakukan pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Setup AJAX global -->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
    }
});
</script>

<!-- AJAX untuk Daftar Transaksi -->
<script>
$(document).on('click', '#transactions-container .pagination a', function(e) {
    e.preventDefault();
    var pageUrl = $(this).attr('href');
    var page = pageUrl.split('page=')[1];
    
    $.ajax({
        url: "{{ route('dashboard.ajax.user.transactions') }}?page=" + page,
        beforeSend: function() {
            $('#transactions-container').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(data) {
            $('#transactions-container').html(data);
        },
        error: function() {
            alert('Gagal memuat data transaksi.');
        }
    });
});
</script>

<!-- AJAX untuk Riwayat Pembayaran -->
<script>
$(document).on('click', '#payments-history-container .pagination a', function(e) {
    e.preventDefault();
    var pageUrl = $(this).attr('href');
    var page = pageUrl.split('payment_page=')[1];
    
    $.ajax({
        url: "{{ route('dashboard.ajax.user.payments') }}?payment_page=" + page,
        beforeSend: function() {
            $('#payments-history-container').html('<div class="text-center p-4"><div class="spinner-border text-info"></div></div>');
        },
        success: function(data) {
            $('#payments-history-container').html(data);
        },
        error: function() {
            alert('Gagal memuat riwayat pembayaran.');
        }
    });
});
</script>
@endpush