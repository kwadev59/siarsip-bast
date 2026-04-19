@extends('layouts.guest', ['title' => 'Masuk'])

@section('content')
    <div class="text-center mb-5">
        <!-- LOGO (SI-ARSIP) -->
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-4 shadow-sm mb-3" style="width: 60px; height: 60px;">
            <i class="bi bi-archive-fill fs-2"></i>
        </div>
        <h2 class="fw-black text-dark mb-1 font-monospace" style="letter-spacing: -1px; font-weight: 800;">
            SI-<span class="text-primary">ARSIP</span>
        </h2>
        
        <!-- Deskripsi Aplikasi -->
        <p class="text-muted small px-4">Sistem Informasi Manajemen Arsip Dokumen Digital PT. BIM PPS.</p>
    

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <!-- Username -->
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-secondary mb-1 ms-1">USERNAME</label>
            <div class="input-group border rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-0"><i class="bi bi-person text-muted"></i></span>
                <input type="text" name="username" value="{{ old('username') }}" 
                    class="form-control border-0 py-2 px-2 shadow-none" placeholder="Username" required autofocus>
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label small fw-bold text-secondary mb-1 ms-1">PASSWORD</label>
            <div class="input-group border rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" 
                    class="form-control border-0 py-2 px-2 shadow-none" placeholder="••••••••" required>
            </div>
            <div class="text-end mt-2">
                <a href="#" class="text-decoration-none small fw-bold" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Lupa Password?</a>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm rounded-3 border-0" style="background: #0d6efd; letter-spacing: 1px;">
            MASUK <i class="bi bi-box-arrow-in-right ms-2"></i>
        </button>

        <div class="text-center mt-5 pt-3">
            <p class="text-muted mb-0" style="font-size: 10px; letter-spacing: 1px;">
                &copy; {{ date('Y') }} PT. BIM PPS
            </p>
            <p class="text-muted mb-2" style="font-size: 9px;">DEVELOPMENT VERSION 2.5</p>
            <a href="#" class="text-decoration-none small fw-bold text-primary" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#aboutModal">About Application</a>
        </div>
    </form>

    <!-- Modal About -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Tentang Aplikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-info-lg fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">SI-ARSIP</h4>
                        <p class="text-muted small">v2.5 Enterprise Edition</p>
                    </div>
                    
                    <p class="text-secondary small leading-relaxed">
                        <span class="fw-bold text-dark">SI-ARSIP</span> adalah sistem manajemen dokumen digital yang dirancang khusus untuk memfasilitasi penyimpanan, pengarsipan, dan pelacakan dokumen Berita Acara (BAST) secara efisien dan aman bagi PT. BIM PPS.
                    </p>
                    
                    <div class="bg-light rounded-4 p-3 mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Developer</span>
                            <span class="text-dark small fw-bold">kwadev59</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Tahun Pembuatan</span>
                            <span class="text-dark small fw-bold">2026</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary w-100 fw-bold rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Lupa Password?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-info-circle-fill fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Instruksi Reset Password</h6>
                    <p class="text-secondary small mb-0">Hubungi <span class="fw-bold text-dark">IT SITE</span> untuk melakukan Reset Password akun Anda.</p>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-0">
                    <button type="button" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            background-color: #fff !important;
        }
        .input-group:focus-within {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>
@endsection
