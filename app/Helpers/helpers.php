<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status) {
        $badges = [
            'pending' => '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>',
            'approved' => '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>',
            'rejected' => '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}

if (!function_exists('getTransactionTypeBadge')) {
    function getTransactionTypeBadge($type) {
        if ($type === 'income') {
            return '<span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Pemasukan</span>';
        }
        
        return '<span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Pengeluaran</span>';
    }
}