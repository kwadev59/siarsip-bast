@extends('layouts.app', ['title' => 'Upload Arsip'])

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-sm-5">
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Upload Arsip</p>
                <h3 class="fw-bold text-dark">Arsip Baru</h3>
                <p class="text-muted small mb-4">Masukkan metadata penting dengan alur yang sederhana dan bersih.</p>

                <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('archives._form', ['archive' => null])

                    <div class="d-flex justify-content-end gap-2 mt-5 pt-4 border-top">
                        <a href="{{ route('archives.index') }}" class="btn btn-light border fw-bold px-4">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Simpan Arsip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Panduan</p>
                <h5 class="fw-bold text-dark mb-4">Input yang baik</h5>
                
                <div class="vstack gap-3">
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-primary">
                        <p class="small text-secondary mb-0">Gunakan nomor dokumen yang unik dan mudah ditelusuri.</p>
                    </div>
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-info">
                        <p class="small text-secondary mb-0">Pilih lokasi fisik sedetail mungkin agar arsip mudah ditemukan.</p>
                    </div>
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-success">
                        <p class="small text-secondary mb-0">Isi deskripsi singkat agar isi dokumen cepat dikenali.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection