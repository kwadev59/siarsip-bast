@extends('layouts.app', ['title' => $title])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-sm-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Master Data</p>
                <h3 class="fw-bold text-dark mb-0">{{ $title }}</h3>
                <p class="text-muted small mt-2 mb-0">{{ $subtitle }}</p>
            </div>
            <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#masterModal" onclick="prepareCreate()">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        @foreach($columns as $index => $column)
                            <th class="{{ $index == 0 ? 'ps-4' : '' }} py-3 text-muted small fw-bold text-uppercase border-0">{{ $column }}</th>
                        @endforeach
                        <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            @foreach(($renderRow)($record) as $index => $value)
                                <td class="{{ $index == 0 ? 'ps-4 text-dark fw-bold' : 'text-secondary' }} py-3 small">
                                    {{ $value }}
                                </td>
                            @endforeach
                            <td class="pe-4 py-3 text-end">
                                <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold" 
                                    onclick="prepareEdit({{ json_encode($record) }})">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                <form action="{{ route(request()->route()->getName(), $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white text-danger border-0 fw-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="text-center py-5 text-muted small italic">Belum ada data {{ $title }} tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer bg-white border-top p-4">
        {{ $records->links() }}
    </div>
    @endif
</div>

<!-- Modal Master Data (Tambah/Edit) -->
<div class="modal fade" id="masterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0" id="modalTitle">Tambah {{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="masterForm" action="{{ route(str_replace('index', 'store', request()->route()->getName())) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body px-4 py-3">
                    <div id="formContent" class="row g-3">
                        <!-- Input dinamis akan diatur via JavaScript jika diperlukan, 
                             tapi kita asumsikan field standar adalah 'name' -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Nama {{ $title }}</label>
                            <input type="text" name="name" id="inputName" class="form-control bg-light px-3 py-2" placeholder="Masukkan nama..." required>
                        </div>
                        
                        {{-- Jika Lokasi, tambahkan field Type --}}
                        @if($title == 'Locations')
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Tipe Lokasi</label>
                            <select name="type" id="inputType" class="form-select bg-light px-3 py-2">
                                <option value="Rak">Rak</option>
                                <option value="Lemari">Lemari</option>
                                <option value="Gudang">Gudang</option>
                            </select>
                        </div>
                        @endif

                        {{-- Jika Kategori, tambahkan field Description --}}
                        @if($title == 'Categories')
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Deskripsi</label>
                            <textarea name="description" id="inputDescription" rows="3" class="form-control bg-light px-3 py-2" placeholder="Masukkan deskripsi..."></textarea>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold small" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm rounded-pill">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const baseAction = "{{ route(str_replace('index', 'store', request()->route()->getName())) }}";

    function prepareCreate() {
        document.getElementById('modalTitle').innerText = "Tambah {{ $title }}";
        document.getElementById('masterForm').action = baseAction;
        document.getElementById('formMethod').value = "POST";
        document.getElementById('inputName').value = "";
        if(document.getElementById('inputType')) document.getElementById('inputType').value = "Rak";
        if(document.getElementById('inputDescription')) document.getElementById('inputDescription').value = "";
    }

    function prepareEdit(record) {
        document.getElementById('modalTitle').innerText = "Edit {{ $title }}";
        // Ganti URL Store menjadi Update (Asumsi Route Resource standard)
        // Jika route Anda manual, sesuaikan format URL-nya
        document.getElementById('masterForm').action = baseAction.replace('/store', '') + '/' + record.id;
        document.getElementById('formMethod').value = "PUT";
        
        document.getElementById('inputName').value = record.name;
        if(document.getElementById('inputType')) document.getElementById('inputType').value = record.type;
        if(document.getElementById('inputDescription')) document.getElementById('inputDescription').value = record.description;
        
        // Buka modal secara manual jika tombol bukan data-bs-toggle
        var myModal = new bootstrap.Modal(document.getElementById('masterModal'));
        myModal.show();
    }
</script>
@endsection
