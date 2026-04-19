@extends('layouts.app', ['title' => 'Audit Log'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-sm-5">
        <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Audit Log</p>
        <h3 class="fw-bold text-dark mb-2">Riwayat Aktivitas</h3>
        <p class="text-muted small mb-4">Semua tindakan penting tersimpan untuk monitoring dan penelusuran.</p>

        <div class="table-responsive mt-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Waktu</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Aksi</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Tabel</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-uppercase border-0">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 py-3 text-secondary small">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                            <td class="py-3 text-primary small fw-bold text-uppercase">{{ $log->action }}</td>
                            <td class="py-3 text-dark small fw-medium">{{ $log->table_name }}</td>
                            <td class="pe-4 py-3 text-secondary small">{{ $log->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top p-4">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection