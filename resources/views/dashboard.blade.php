@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle text-primary rounded-3 p-3 me-3">
                    <i class="bi bi-files fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem;">Total Arsip</small>
                    <h3 class="fw-bold mb-0">{{ $stats['archives'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-success-subtle text-success rounded-3 p-3 me-3">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem;">Arsip Aktif</small>
                    <h3 class="fw-bold mb-0">{{ $stats['active_archives'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning-subtle text-warning rounded-3 p-3 me-3">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem;">Dipinjam</small>
                    <h3 class="fw-bold mb-0">{{ $stats['borrowed_archives'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-danger-subtle text-danger rounded-3 p-3 me-3">
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem;">Terlambat</small>
                    <h3 class="fw-bold mb-0">{{ $stats['overdue_borrowings'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Latest Archives Table -->
    <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h5 class="fw-bold mb-0">Arsip Terbaru</h5>
                <a href="{{ route('archives.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-muted small fw-bold text-uppercase">Nomor & Tanggal</th>
                            <th class="text-muted small fw-bold text-uppercase">Judul</th>
                            <th class="text-muted small fw-bold text-uppercase">Divisi</th>
                            <th class="text-muted small fw-bold text-uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latest_archives as $archive)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-primary">{{ $archive->document_number }}</div>
                                <div class="small text-muted">{{ optional($archive->document_date)->format('d M Y') }}</div>
                            </td>
                            <td><span class="text-dark fw-medium">{{ $archive->title }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $archive->division->name ?? '-' }}</span></td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-success',
                                        'borrowed' => 'bg-warning text-dark',
                                        'archived' => 'bg-secondary'
                                    ];
                                @endphp
                                <span class="badge rounded-pill {{ $statusClasses[$archive->status] ?? 'bg-info' }}">
                                    {{ ucfirst($archive->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada arsip tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Summary -->
    <div class="col-lg-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-4">Ringkasan Sistem</h6>
                <div class="list-group list-group-flush">
                    @foreach ([
                        'Divisi' => $stats['divisions'],
                        'Kategori' => $stats['categories'],
                        'Lokasi' => $stats['locations'],
                        'Audit Log' => $stats['audit_logs'],
                    ] as $label => $value)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <span class="text-muted">{{ $label }}</span>
                        <span class="fw-bold text-dark">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Log Terbaru</h6>
                <div class="vstack gap-3 mt-3">
                    @forelse($latest_audit_logs as $log)
                    <div class="p-3 bg-light rounded-3">
                        <div class="d-flex justify-content-between">
                            <span class="small fw-bold text-uppercase text-primary">{{ $log->action }}</span>
                            <span class="small text-muted">{{ $log->created_at->format('d M') }}</span>
                        </div>
                        <p class="small text-dark mb-0 mt-1">{{ $log->created_at->format('H:i') }} - {{ $log->table_name }}</p>
                    </div>
                    @empty
                    <p class="small text-muted">Tidak ada aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
