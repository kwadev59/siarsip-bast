@extends('layouts.app', ['title' => 'Manajemen User'])
@section('content')
<div class="card-c">
    <div class="card-c-head">
        <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width:320px">
            <div class="input-grp flex-grow-1"><span class="ig-icon"><i class="bi bi-search"></i></span><input type="text" name="q" value="{{ request('q') }}" class="fi" placeholder="Cari user..."></div>
            <button type="submit" class="btn-a"><i class="bi bi-funnel"></i></button>
        </form>
        <button type="button" class="btn-a" data-bs-toggle="modal" data-bs-target="#userModal"><i class="bi bi-plus-lg"></i> User Baru</button>
    </div>
    <div class="table-responsive">
        <table class="tbl">
            <thead><tr><th>User</th><th>Username</th><th>Role</th><th>Login Terakhir</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><div class="d-flex align-items-center gap-3"><div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;font-weight:700;font-size:.75rem;display:grid;place-items:center;flex-shrink:0">{{ substr($user->name,0,1) }}</div><div style="font-weight:600;color:#0f172a;font-size:.825rem">{{ $user->name }}</div></div></td>
                    <td style="color:#64748b;font-size:.8rem;font-family:monospace">{{ $user->username }}</td>
                    <td>@php $rc=['superadmin'=>'#dc2626','admin'=>'#4f46e5','staff'=>'#64748b'];$c=$rc[$user->role]??'#64748b';@endphp<span style="display:inline-flex;padding:3px 10px;border-radius:20px;font-size:.65rem;font-weight:600;text-transform:capitalize;background:{{ $c }}15;color:{{ $c }}">{{ $user->role }}</span></td>
                    <td style="color:#94a3b8;font-size:.8rem">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : '-' }}</td>
                    <td style="text-align:right"><div class="dropdown d-inline-block"><button class="btn-g" style="padding:5px 8px;border:none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button><ul class="dropdown-menu dropdown-menu-end"><li><button class="dropdown-item" onclick="editUser({{ $user->id }},'{{ addslashes($user->name) }}','{{ $user->username }}','{{ $user->role }}','{{ $user->division_id ?? '' }}')"><i class="bi bi-pencil me-2 text-muted"></i>Edit</button></li><li><hr class="dropdown-divider"></li><li><form action="{{ route('users.destroy',$user) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</button></form></li></ul></div></td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-s"><i class="bi bi-people"></i>Belum ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div style="padding:16px 24px;border-top:1px solid #e2e8f0;background:#fff">{{ $users->links() }}</div>@endif
</div>
@push('modals')
<div class="modal fade" id="userModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h6 style="font-weight:600;margin:0">User Baru</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form action="{{ route('users.store') }}" method="POST" id="userForm">@csrf<div class="modal-body"><input type="hidden" name="id" id="userId"><div class="row g-3"><div class="col-6"><label class="fl">Nama</label><input name="name" id="userName" class="fi" required></div><div class="col-6"><label class="fl">Username</label><input name="username" id="userUsername" class="fi" required></div><div class="col-6"><label class="fl">Password</label><input type="password" name="password" id="userPassword" class="fi" required></div><div class="col-6"><label class="fl">Role</label><select name="role" id="userRole" class="fi"><option value="staff">Staff</option><option value="admin">Admin</option><option value="superadmin">Superadmin</option></select></div><div class="col-12"><label class="fl">Divisi</label><select name="division_id" id="userDivision" class="fi"><option value="">Tanpa Divisi</option>@foreach($divisions??[] as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div></div></div><div class="modal-footer"><button type="button" class="btn-g" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn-a">Simpan</button></div></form></div></div></div>
@endpush
<script>
function editUser(id,n,u,r,d){document.getElementById('userId').value=id;document.getElementById('userName').value=n;document.getElementById('userUsername').value=u;document.getElementById('userRole').value=r;document.getElementById('userDivision').value=d;document.getElementById('userPassword').removeAttribute('required');document.getElementById('userForm').action='{{ route('users.update',0) }}'.replace('/0','/'+id);new bootstrap.Modal(document.getElementById('userModal')).show();}
document.getElementById('userModal')?.addEventListener('hidden.bs.modal',function(){document.getElementById('userForm').reset();document.getElementById('userId').value='';document.getElementById('userPassword').setAttribute('required','');document.getElementById('userForm').action='{{ route('users.store') }}';});
</script>
@endsection