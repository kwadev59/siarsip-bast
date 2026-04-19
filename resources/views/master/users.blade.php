@extends('layouts.app', ['title' => 'Users'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-sm-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Master Data</p>
                <h3 class="fw-bold text-dark mb-0">User Management</h3>
                <p class="text-muted small mt-2 mb-0">Daftar akun aktif beserta role dan divisinya.</p>
            </div>
            <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#userModal" onclick="prepareCreate()">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User
            </button>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase border-0">Name</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Username</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Email</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Role</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase border-0">Division</th>
                        <th class="pe-4 py-3 text-end text-muted small fw-bold text-uppercase border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3 text-dark fw-bold small">{{ $user->name }}</td>
                            <td class="py-3 text-primary fw-semibold small">{{ $user->username ?: '-' }}</td>
                            <td class="py-3 text-secondary small">{{ $user->email }}</td>
                            <td class="py-3">
                                <span class="badge {{ $user->role === 'superadmin' ? 'bg-danger' : 'bg-primary' }} rounded-pill px-3 py-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-3 text-secondary small">{{ $user->division->name ?? '-' }}</td>
                            <td class="pe-4 py-3 text-end">
                                <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold" 
                                    onclick="prepareEdit({{ json_encode($user) }})">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white text-danger border-0 fw-bold">Hapus</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-top p-4">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Modal User (Tambah/Edit) -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0" id="modalTitle">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="userForm" action="{{ route('users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" id="inputName" class="form-control bg-light px-3 py-2" required placeholder="Nama lengkap">
                            <div class="form-text" style="font-size: 0.7rem;">Username akan dibuat otomatis: <span class="text-primary fw-bold">nama_bimpps</span></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Email</label>
                            <input type="email" name="email" id="inputEmail" class="form-control bg-light px-3 py-2" required placeholder="email@perusahaan.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Password</label>
                            <input type="password" name="password" id="inputPassword" class="form-control bg-light px-3 py-2" placeholder="Biarkan kosong untuk default: 123456">
                            <div class="form-text" id="passwordHint" style="font-size: 0.7rem;">Password minimal 6 karakter.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Role</label>
                            <select name="role" id="inputRole" class="form-select bg-light px-3 py-2" required>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Divisi</label>
                            <select name="division_id" id="inputDivision" class="form-select bg-light px-3 py-2" required>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
    function prepareCreate() {
        document.getElementById('modalTitle').innerText = "Tambah User";
        document.getElementById('userForm').action = "{{ route('users.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('inputName').value = "";
        document.getElementById('inputEmail').value = "";
        document.getElementById('inputPassword').value = "";
        document.getElementById('inputPassword').required = false;
        document.getElementById('inputRole').value = "admin";
    }

    function prepareEdit(user) {
        document.getElementById('modalTitle').innerText = "Edit User";
        document.getElementById('userForm').action = "/users/" + user.id;
        document.getElementById('formMethod').value = "PUT";
        
        document.getElementById('inputName').value = user.name;
        document.getElementById('inputEmail').value = user.email;
        document.getElementById('inputPassword').value = "";
        document.getElementById('inputPassword').required = false;
        document.getElementById('inputRole').value = user.role;
        document.getElementById('inputDivision').value = user.division_id;
        
        var myModal = new bootstrap.Modal(document.getElementById('userModal'));
        myModal.show();
    }
</script>
@endsection
