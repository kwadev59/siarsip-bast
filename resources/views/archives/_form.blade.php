@php
    $archive = $archive ?? null;
    $isCompact = $isCompact ?? false;
@endphp

<div class="{{ $isCompact ? 'row g-3' : 'space-y-6' }}">
    <!-- Section 1: Utama -->
    <div class="{{ $isCompact ? 'col-12' : '' }}">
        <div class="d-flex align-items-center mb-2">
            <span class="badge bg-primary me-2" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"> </span>
            <h6 class="fw-bold mb-0 text-dark small">Informasi Utama</h6>
        </div>
        
        <div class="row g-2">
            <div class="col-12">
                <div class="bg-primary-subtle border border-primary-subtle text-primary rounded-2 px-3 py-2 small fw-bold mb-2">
                    Auto: 0000-DIVISI-PT.BIM-PPS-{{ now()->format('m/Y') }}
                </div>
            </div>
            <div class="col-md-{{ $isCompact ? '12' : '6' }}">
                <label class="form-label mb-1 text-secondary fw-bold" style="font-size: 0.7rem;">Judul Arsip</label>
                <input name="title" value="{{ old('title', $archive->title ?? '') }}" placeholder="Judul arsip" class="form-control form-control-sm bg-light" required>
            </div>
            <div class="col-12">
                <label class="form-label mb-1 text-secondary fw-bold" style="font-size: 0.7rem;">Deskripsi Singkat</label>
                <textarea name="description" rows="{{ $isCompact ? '2' : '3' }}" placeholder="Tambahkan deskripsi..." class="form-control form-control-sm bg-light">{{ old('description', $archive->description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 2: Klasifikasi -->
    <div class="{{ $isCompact ? 'col-12 mt-3 pt-3 border-top' : 'mt-4 pt-4 border-top' }}">
        <div class="d-flex align-items-center mb-2">
            <span class="badge bg-info me-2" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"> </span>
            <h6 class="fw-bold mb-0 text-dark small">Klasifikasi & Lokasi</h6>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label mb-1 text-secondary fw-bold" style="font-size: 0.7rem;">Kategori</label>
                <select name="category_id" class="form-select form-select-sm bg-light" required>
                    <option value="">Pilih...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $archive->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1 text-secondary fw-bold" style="font-size: 0.7rem;">Divisi</label>
                <select name="division_id" class="form-select form-select-sm bg-light" required>
                    <option value="">Pilih...</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" @selected(old('division_id', $archive->division_id ?? null) == $division->id)>{{ $division->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1 text-secondary fw-bold" style="font-size: 0.7rem;">Lokasi</label>
                <select name="location_id" class="form-select form-select-sm bg-light" required>
                    <option value="">Pilih...</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" @selected(old('location_id', $archive->location_id ?? null) == $location->id)>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Section 3: File -->
    <div class="{{ $isCompact ? 'col-12 mt-3 pt-3 border-top' : 'mt-4 pt-4 border-top' }}">
        <div class="d-flex align-items-center mb-2">
            <span class="badge bg-success me-2" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"> </span>
            <h6 class="fw-bold mb-0 text-dark small">Dokumen</h6>
        </div>
        <input type="file" name="file" class="form-control form-control-sm bg-light" required>
        <div class="form-text mt-1" style="font-size: 0.65rem;">Format PDF disarankan. Maksimal 10MB.</div>
    </div>
</div>
