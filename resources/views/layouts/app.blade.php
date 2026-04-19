<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'E-Arsip' }} - System</title>
    
    <!-- Hybrid Assets: CDN dengan Fallback ke Lokal -->
    <link id="bootstrap-css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          onerror="this.onerror=null;this.href='{{ asset('assets/css/bootstrap.min.css') }}';">
    
    <link id="bootstrap-icons" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          onerror="this.onerror=null;this.href='{{ asset('assets/css/bootstrap-icons.min.css') }}';">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #0d6efd;
            --bg-body: #f8f9fa;
            --sidebar-dark-bg: #1e1e2d;
            --sidebar-dark-hover: #2b2b40;
            --content-padding: 1.5rem;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body);
            overflow-x: hidden;
            font-size: 0.85rem;
        }
        
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-dark-bg);
            position: fixed;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
            z-index: 1000;
        }
        
        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }
        
        #sidebar .sidebar-logo {
            padding: 1.5rem;
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu-wrapper::-webkit-scrollbar { width: 4px; }
        .sidebar-menu-wrapper::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            gap: 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            text-decoration: none;
            border-radius: 0.75rem;
            white-space: nowrap;
        }

        #sidebar .nav-link i { font-size: 1.1rem; min-width: 24px; }
        #sidebar .nav-link:hover { background-color: var(--sidebar-dark-hover); color: #ffffff; }
        #sidebar .nav-link.active { background-color: var(--primary-color); color: #ffffff; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2); }
        
        #sidebar .section-title {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.25);
            margin: 1.5rem 0 0.5rem 1.75rem;
        }

        #sidebar.collapsed .sidebar-text, 
        #sidebar.collapsed .section-title { display: none; }
        #sidebar.collapsed .nav-link { justify-content: center; padding: 0.75rem 0; margin: 0.2rem 0.5rem; }

        #content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            transition: all 0.25s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        #content.expanded {
            width: calc(100% - var(--sidebar-collapsed-width));
            margin-left: var(--sidebar-collapsed-width);
        }

        .top-navbar {
            background: #ffffff;
            padding: 0.75rem var(--content-padding);
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .main-container {
            padding: var(--content-padding);
            flex-grow: 1;
        }

        .btn-toggle-sidebar {
            background: var(--bg-body);
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 0.4rem 0.6rem;
            color: #495057;
        }

        .user-dropdown-toggle {
            padding: 0.35rem 0.75rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
            cursor: pointer;
        }
        .user-dropdown-toggle:hover { background: #f8f9fa; }

        @media (max-width: 992px) {
            #sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            #sidebar.active { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
            #sidebar.collapsed { width: var(--sidebar-width); margin-left: 0; }
        }

        .card { border: none; border-radius: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .toast-container { z-index: 2000; }
        .toast { border: none; border-radius: 1rem; overflow: hidden; }
    </style>
</head>
<body>

    @auth
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-archive-fill text-primary fs-4 me-2"></i>
            <span class="fs-5 fw-bold text-white sidebar-text">SI ARSIP</span>
        </div>

        <div class="sidebar-menu-wrapper">
            <div class="nav flex-column mt-2">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="{{ route('archives.index') }}" class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> <span class="sidebar-text">Data Arsip</span>
                </a>
                <a href="{{ route('borrowings.index') }}" class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> <span class="sidebar-text">Peminjaman</span>
                </a>
                <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> <span class="sidebar-text">Audit Log</span>
                </a>

                @if(auth()->user()->role === 'superadmin')
                <p class="section-title">Master Data</p>
                <a href="{{ route('divisions.index') }}" class="nav-link {{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> <span class="sidebar-text">Divisi</span>
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> <span class="sidebar-text">Kategori</span>
                </a>
                <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> <span class="sidebar-text">Lokasi</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span class="sidebar-text">User Management</span>
                </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <div id="content">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-toggle-sidebar shadow-sm" id="sidebarCollapse">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item text-muted small" style="font-size: 0.7rem;">System</li>
                        </ol>
                    </nav>
                    <h5 class="fw-bold mb-0">{{ $title ?? 'Dashboard' }}</h5>
                </div>
            </div>

            <div class="dropdown">
                <div class="user-dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark" style="font-size: 0.8rem; line-height: 1.2;">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.65rem;">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 0.9rem;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" style="border-radius: 1rem; min-width: 200px;">
                    <li class="px-3 py-2 border-bottom mb-2 d-sm-none">
                        <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }}</div>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item d-flex align-items-center gap-2 fw-bold py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key fs-5 text-muted"></i> Ganti Password
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 fw-bold py-2 rounded-3">
                                <i class="bi bi-box-arrow-right fs-5"></i> Keluar Sistem
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="main-container">
            @yield('content')
        </main>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <span id="successMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span id="errorMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    @else
        <div class="container py-5">
            @yield('content')
        </div>
    @endauth

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control bg-light" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Baru</label>
                            <input type="password" name="new_password" class="form-control bg-light" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control bg-light" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 pt-0">
                        <button type="button" class="btn btn-link text-muted text-decoration-none small fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Perbarui Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hybrid JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            onerror="this.onerror=null;this.src='{{ asset('assets/js/bootstrap.bundle.min.js') }}';"></script>
            
    <script>
        document.getElementById('sidebarCollapse')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            if (window.innerWidth > 992) {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            } else {
                sidebar.classList.toggle('active');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                document.getElementById('successMessage').innerText = "{{ session('success') }}";
                new bootstrap.Toast(document.getElementById('successToast')).show();
            @endif
            @if($errors->any())
                document.getElementById('errorMessage').innerText = "{{ $errors->first() }}";
                new bootstrap.Toast(document.getElementById('errorToast')).show();
            @endif
        });
    </script>
    @stack('modals')
</body>
</html>
