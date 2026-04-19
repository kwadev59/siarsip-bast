@extends('layouts.app', ['title' => 'Data Arsip'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-end gap-3 mb-4">
            <div>
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Data Arsip</p>
                <h3 class="fw-bold text-dark mb-2">Daftar Arsip</h3>
                <p class="text-muted small mb-0">Cari dan kelola dokumen dengan tampilan yang ringkas dan mudah dipahami.</p>
            </div>
            <button type="button" class="btn btn-primary shadow-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#archiveModal">
                <i class="bi bi-cloud-arrow-up me-2"></i> Upload Arsip Baru
            </button>
        </div>

        <form class="row g-3" method="GET">
            <div class="col-xl-4 col-md-6">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nomor atau judul dokumen" class="form-control bg-light">
            </div>
            <div class="col-xl-2 col-md-6">
                <select name="category_id" class="form-select bg-light">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-6">
                <select name="division_id" class="form-select bg-light">
                    <option value="">Semua Divisi</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>{{ $division->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-6">
                <select name="location_id" class="form-select bg-light">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" @selected(request('location_id') == $location->id)>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-6">
                <select name="status" class="form-select bg-light">
                    <option value="">Semua Status</option>
                    @foreach(['active', 'borrowed', 'archived'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-12 col-md-12 text-end">
                <button type="submit" class="btn btn-secondary px-4 fw-bold shadow-sm">Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Dokumen</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Deskripsi</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Divisi</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Lokasi</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Status</th>
                        <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archives as $archive)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-primary">{{ $archive->document_number }}</div>
                                <div class="small fw-medium text-dark mt-1">{{ $archive->title }}</div>
                                <div class="small text-muted mt-1">{{ optional($archive->document_date)->format('d M Y') }}</div>
                            </td>
                            <td class="py-3 text-secondary small" style="max-width: 250px;">
                                <div class="text-truncate" title="{{ $archive->description }}">
                                    {{ $archive->description ?: '-' }}
                                </div>
                            </td>
                            <td class="py-3 text-secondary small">{{ $archive->division->name ?? '-' }}</td>
                            <td class="py-3 text-secondary small">{{ $archive->location->name ?? '-' }}</td>
                            <td class="py-3">
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
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('archives.show', $archive) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada arsip yang cocok dengan filter saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($archives->hasPages())
    <div class="card-footer bg-white border-top p-4">
        {{ $archives->links() }}
    </div>
    @endif
</div>
@endsection

@push('modals')
    @include('archives._upload_panel')
@endpush