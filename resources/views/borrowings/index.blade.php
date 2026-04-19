@extends('layouts.app', ['title' => 'Log Peminjaman'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-sm-5">
        <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Peminjaman</p>
        <h3 class="fw-bold text-dark mb-2">Log Peminjaman</h3>
        <p class="text-muted small mb-4">Pantau dokumen yang dipinjam dan status pengembaliannya.</p>

        <div class="table-responsive mt-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Arsip</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Peminjam</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Dipinjam</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-uppercase border-0">Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowingLogs as $log)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-primary">{{ $log->archive->document_number }}</div>
                                <div class="small text-secondary mt-1">{{ $log->archive->title }}</div>
                            </td>
                            <td class="py-3 text-dark small fw-medium">{{ $log->borrower_name }}</td>
                            <td class="py-3 text-muted small">{{ $log->borrowed_at->format('d M Y H:i') }}</td>
                            <td class="pe-4 py-3">
                                @if($log->returned_at)
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check2-circle me-1"></i>{{ $log->returned_at->format('d M Y H:i') }}</span>
                                @else
                                    <form action="{{ route('borrowings.return', $log) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">Return</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada log peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($borrowingLogs->hasPages())
    <div class="card-footer bg-white border-top p-4">
        {{ $borrowingLogs->links() }}
    </div>
    @endif
</div>
@endsection