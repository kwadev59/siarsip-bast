@extends('layouts.app', ['title' => 'Edit Arsip'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 p-sm-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start mb-4 gap-3">
            <div>
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Edit Arsip</p>
                <h3 class="fw-bold text-dark">Perbarui Data Arsip</h3>
                <p class="text-muted small mb-0">Ubah metadata atau file dokumen dengan tampilan yang lebih sederhana.</p>
            </div>
            <a href="{{ route('archives.show', $archive) }}" class="btn btn-light border px-4 fw-bold">Kembali ke Detail</a>
        </div>

        <form action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Nomor Dokumen</label>
                    <input name="document_number" value="{{ old('document_number', $archive->document_number) }}" class="form-control bg-light">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Judul Arsip</label>
                    <input name="title" value="{{ old('title', $archive->title) }}" class="form-control bg-light">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Kategori</label>
                    <select name="category_id" class="form-select bg-light">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($archive->category_id == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Divisi</label>
                    <select name="division_id" class="form-select bg-light">
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" @selected($archive->division_id == $division->id)>{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Lokasi Fisik</label>
                    <select name="location_id" class="form-select bg-light">
                        <option value="">Pilih lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @selected($archive->location_id == $location->id)>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Tanggal Dokumen</label>
                    <input type="date" name="document_date" value="{{ old('document_date', $archive->document_date?->format('Y-m-d')) }}" class="form-control bg-light">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Status</label>
                    <select name="status" class="form-select bg-light">
                        @foreach(['active', 'borrowed', 'archived'] as $status)
                            <option value="{{ $status }}" @selected($archive->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">Ganti File</label>
                    <input type="file" name="file" class="form-control bg-light">
                    <div class="form-text small">Biarkan kosong jika tidak ingin mengubah file.</div>
                </div>
                
                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary">Deskripsi</label>
                    <textarea name="description" rows="5" class="form-control bg-light">{{ old('description', $archive->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-5 pt-4 border-top">
                <a href="{{ route('archives.show', $archive) }}" class="btn btn-light border px-4 fw-bold">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection