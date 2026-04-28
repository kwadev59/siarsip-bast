@extends('layouts.app', ['title' => 'Detail Arsip'])

@section('content')
<div class="row g-4">
    <!-- Left Column: Metadata & Action -->
    <div class="col-xl-4 col-lg-5 order-2 order-lg-1">
        
        <!-- Header Info Card -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 1.25rem;">
            <div class="bg-primary p-1"></div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> {{ $archive->status }}
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle border-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm small">
                            <li><a class="dropdown-item" href="{{ route('archives.edit', $archive) }}"><i class="bi bi-pencil me-2"></i>Edit Data</a></li>
                            @if($downloadUrl)
                                <li><a class="dropdown-item text-primary" href="{{ $downloadUrl }}"><i class="bi bi-download me-2"></i>Download PDF</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('archives.destroy', $archive) }}" method="POST" onsubmit="return confirm('Hapus arsip ini?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus Arsip</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <h4 class="fw-extrabold text-dark mb-1">{{ $archive->title }}</h4>
                <p class="text-muted mb-4 font-monospace" style="font-size: 0.75rem; letter-spacing: -0.2px;">{{ $archive->document_number }}</p>

                <div class="row g-3">
                    @foreach ([
                        ['label' => 'Kategori', 'value' => $archive->category->name ?? '-', 'icon' => 'bi-tag'],
                        ['label' => 'Divisi', 'value' => $archive->division->name ?? '-', 'icon' => 'bi-building'],
                        ['label' => 'Lokasi', 'value' => $archive->location->name ?? '-', 'icon' => 'bi-geo-alt'],
                        ['label' => 'Tanggal', 'value' => optional($archive->document_date)->format('d M Y') ?: '-', 'icon' => 'bi-calendar-event'],
                    ] as $item)
                    <div class="col-6">
                        <div class="p-2">
                            <label class="d-block text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem; letter-spacing: 1px;">
                                <i class="{{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                            </label>
                            <span class="text-dark fw-semibold small">{{ $item['value'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-top">
                    <label class="d-block text-muted fw-bold text-uppercase mb-2" style="font-size: 0.6rem; letter-spacing: 1px;">Deskripsi</label>
                    <div class="bg-light rounded-3 p-3 text-secondary small" style="line-height: 1.6; border-left: 3px solid #dee2e6;">
                        {{ $archive->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing Modern Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 1.25rem; background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-3 p-2 me-3 shadow-sm">
                        <i class="bi bi-person-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Pinjamkan Arsip</h6>
                        <p class="text-muted small mb-0">Catat serah terima fisik</p>
                    </div>
                </div>

                <form action="{{ route('borrowings.borrow', $archive) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="form-floating">
                            <input name="borrower_name" class="form-control border-0 bg-white shadow-sm px-3" id="nameInput" placeholder="Nama" style="border-radius: 0.75rem;" required>
                            <label for="nameInput" class="text-muted small">Nama Peminjam</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input name="borrower_division" class="form-control border-0 bg-white shadow-sm px-3" id="divInput" placeholder="Divisi" style="border-radius: 0.75rem;">
                            <label for="divInput" class="text-muted small">Divisi/Unit</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2 fw-bold shadow-sm" style="border-radius: 0.75rem; letter-spacing: 0.5px;">
                        PINJAMKAN SEKARANG <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Latest History Small -->
        <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 1px;">Riwayat Terakhir</h6>
                <div class="vstack gap-3">
                    @forelse($archive->borrowingLogs->take(3) as $log)
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-{{ $log->returned_at ? 'light' : 'warning-subtle' }} rounded-circle p-2">
                            <i class="bi bi-clock-history small text-{{ $log->returned_at ? 'muted' : 'warning' }}"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="small fw-bold text-dark text-nowrap text-truncate">{{ $log->borrower_name }}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">{{ $log->borrowed_at->format('d M, H:i') }}</div>
                        </div>
                        <span class="badge {{ $log->returned_at ? 'bg-light text-muted border' : 'bg-warning text-dark' }} rounded-pill" style="font-size: 0.6rem;">
                            {{ $log->returned_at ? 'Returned' : 'In Use' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-muted small italic mb-0">Belum ada riwayat.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: PDF Previewer High-End -->
    <div class="col-xl-8 col-lg-7 order-1 order-lg-2">
        <div class="card border-0 shadow-sm" style="border-radius: 1.25rem; height: calc(100vh - 120px); background: #525659;">
            <div class="card-header bg-dark border-0 py-2 px-4 d-flex justify-content-between align-items-center" style="border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">
                <div class="d-flex align-items-center text-white">
                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-6 me-2"></i>
                    <span class="small fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">Pratinjau Dokumen Digital</span>
                </div>
                <div class="btn-group shadow-sm">
                    <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-sm btn-outline-light border-0 py-0"><i class="bi bi-fullscreen" style="font-size: 0.7rem;"></i></a>
                </div>
            </div>
            <div class="card-body p-0 position-relative d-flex justify-content-center overflow-hidden">
                @if($archive->file_path)
                    <embed src="{{ asset('storage/' . $archive->file_path) }}" type="application/pdf" width="100%" height="100%" style="border: none; position: absolute; top:0; left:0;" />
                @else
                    <div class="text-center my-auto p-5 text-white-50">
                        <i class="bi bi-file-earmark-pdf fs-1"></i>
                        <p class="mt-3 small fw-bold">DOKUMEN TIDAK DITEMUKAN</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
