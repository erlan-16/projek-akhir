@extends('layouts.app')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-bold"><i class="fas fa-chart-line me-2"></i>Dashboard Bendahara</h1>
        <p class="text-muted">Kelola keuangan dan pembayaran kas kelas</p>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-gradient rounded-circle p-3" style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-arrow-up text-white fs-5"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Total Pemasukan</div>
                        <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-gradient rounded-circle p-3" style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-arrow-down text-white fs-5"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Total Pengeluaran</div>
                        <div class="fs-5 fw-bold text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-gradient rounded-circle p-3" style="width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-wallet text-white fs-5"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="text-muted small">Saldo Akhir</div>
                        <div class="fs-5 fw-bold text-primary">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PEMBAYARAN PENDING -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
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
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPayments as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->user->name }}</strong>
                                        @if($payment->description)
                                            <br><small class="text-muted">{{ $payment->description }}</small>
                                        @endif
                                    </td>
                                    <td><strong class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                                    <td class="text-muted">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('payments.approve', $payment) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('payments.reject', $payment) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak?')">
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
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle mb-2" style="font-size: 3rem;"></i>
                        <p>Tidak ada pembayaran pending</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- FORM + TABEL TRANSAKSI GABUNG -->
<div class="row mb-4">
    <!-- FORM TAMBAH -->
    <div class="col-lg-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-plus text-primary me-2"></i>Tambah Transaksi</h5>
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

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Transaksi</label>
                        <select class="form-select" name="type" required>
                            <option value="">-- Pilih --</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" class="form-control" name="amount" placeholder="0" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TABEL TRANSAKSI GABUNG (PEMASUKAN + PENGELUARAN) -->
        <div class="col-lg-8 mb-3" id="admin-transactions-container">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i>Transaksi Terbaru
                        <span class="badge bg-primary ms-2">{{ $totalTransCount }}</span>
                    </h5>
            </div>
            <div class="card-body p-0">
                @if($transData->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th style="text-align: right;">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transData as $trans)
                                <tr>
                                    <td><small class="text-muted">{{ $trans->created_at->format('d M Y') }}</small></td>
                                    <td>
                                        @if($trans->type == 'income')
                                            <span class="badge bg-success">Pemasukan</span>
                                        @else
                                            <span class="badge bg-danger">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($trans->description, 30) }}</td>
                                    <td style="text-align: right;">
                                        <strong class="{{ $trans->type == 'income' ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($trans->amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if($transTotalPages > 1)
                    <nav class="p-2 border-top">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            @if($transPage > 1)
                                <li class="page-item"><a class="page-link" href="?trans_page=1">«</a></li>
                                <li class="page-item"><a class="page-link" href="?trans_page={{ $transPage - 1 }}">‹</a></li>
                            @endif

                            @for($i = 1; $i <= $transTotalPages; $i++)
                                <li class="page-item {{ $i == $transPage ? 'active' : '' }}">
                                    <a class="page-link" href="?trans_page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($transPage < $transTotalPages)
                                <li class="page-item"><a class="page-link" href="?trans_page={{ $transPage + 1 }}">›</a></li>
                                <li class="page-item"><a class="page-link" href="?trans_page={{ $transTotalPages }}">»</a></li>
                            @endif
                        </ul>
                    </nav>
                    <div class="text-center p-2">
                        <small class="text-muted">Hal {{ $transPage }} dari {{ $transTotalPages }}</small>
                    </div>
                    @endif
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="fas fa-inbox mb-2" style="font-size: 2rem;"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- PILIH BULAN -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2"><i class="fas fa-calendar me-2"></i>Pilih Bulan</label>
                        <select class="form-select" onchange="window.location='?month='+this.value">
                            <option value="07" {{ $selectedMonth == '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ $selectedMonth == '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ $selectedMonth == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedMonth == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $selectedMonth == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedMonth == '12' ? 'selected' : '' }}>Desember</option>
                            <option value="01" {{ $selectedMonth == '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ $selectedMonth == '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ $selectedMonth == '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ $selectedMonth == '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ $selectedMonth == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ $selectedMonth == '06' ? 'selected' : '' }}>Juni</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Total Pemasukan Bulan Ini:</small>
                                <h5 class="fw-bold text-success">Rp {{ number_format($monthlyIncomeTotal, 0, ',', '.') }}</h5>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Total Pengeluaran Bulan Ini:</small>
                                <h5 class="fw-bold text-danger">Rp {{ number_format($monthlyExpenseTotal, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TRANSAKSI PER BULAN (GABUNG) -->
<div class="row" id="monthly-transactions-container">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>Transaksi Bulan Ini
                    <span class="badge bg-secondary ms-2">{{ $totalMonthlyCount }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($monthlyData->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th style="text-align: right;">Nominal</th>
                                    <th>Dari/Dicatat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyData as $item)
                                <tr>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($item->type == 'income')
                                            <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Pemasukan</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->description }}</td>
                                    <td class="fw-bold {{ $item->type == 'income' ? 'text-success' : 'text-danger' }}" style="text-align: right;">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($item->payment)
                                            {{ $item->payment->user->name }}
                                        @else
                                            {{ $item->creator->name }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION BULAN -->
                    @if($monthTotalPages > 1)
                    <nav class="p-2">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            @if($monthPage > 1)
                                <li class="page-item"><a class="page-link" href="?month={{ $selectedMonth }}&month_page=1">«</a></li>
                                <li class="page-item"><a class="page-link" href="?month={{ $selectedMonth }}&month_page={{ $monthPage - 1 }}">‹</a></li>
                            @endif

                            @for($i = 1; $i <= $monthTotalPages; $i++)
                                <li class="page-item {{ $i == $monthPage ? 'active' : '' }}">
                                    <a class="page-link" href="?month={{ $selectedMonth }}&month_page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($monthPage < $monthTotalPages)
                                <li class="page-item"><a class="page-link" href="?month={{ $selectedMonth }}&month_page={{ $monthPage + 1 }}">›</a></li>
                                <li class="page-item"><a class="page-link" href="?month={{ $selectedMonth }}&month_page={{ $monthTotalPages }}">»</a></li>
                            @endif
                        </ul>
                    </nav>
                    <div class="text-center p-2">
                        <small class="text-muted">Hal {{ $monthPage }} dari {{ $monthTotalPages }}</small>
                    </div>
                    @endif
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="fas fa-inbox mb-2" style="font-size: 2rem;"></i>
                        <p>Belum ada transaksi bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<!-- jQuery -->
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

<!-- AJAX untuk Transaksi Terbaru -->
<script>
$(document).on('click', '#admin-transactions-container .pagination a', function(e) {
    e.preventDefault();
    var pageUrl = $(this).attr('href');
    var page = pageUrl.split('trans_page=')[1];
    
    $.ajax({
        url: "{{ route('dashboard.ajax.admin.transactions') }}?trans_page=" + page,
        beforeSend: function() {
            $('#admin-transactions-container').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(data) {
            $('#admin-transactions-container').html(data);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal memuat data transaksi.');
        }
    });
});
</script>

<!-- AJAX untuk Transaksi Bulanan -->
<script>
$(document).on('click', '#monthly-transactions-container .pagination a', function(e) {
    e.preventDefault();
    var pageUrl = $(this).attr('href');
    var urlParams = new URLSearchParams(pageUrl.split('?')[1]);
    var month = urlParams.get('month');
    var monthPage = urlParams.get('month_page');
    
    $.ajax({
        url: "{{ route('dashboard.ajax.admin.monthly') }}?month=" + month + "&month_page=" + monthPage,
        beforeSend: function() {
            $('#monthly-transactions-container').html('<div class="text-center p-4"><div class="spinner-border text-secondary"></div></div>');
        },
        success: function(data) {
            $('#monthly-transactions-container').html(data);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal memuat data transaksi bulanan.');
        }
    });
});
</script>
@endpush