<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('master.users', [
            'users' => User::with('division')->latest()->paginate(10),
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6', // Minimal 6 karakter
            'role' => 'required|string|in:admin,superadmin',
            'division_id' => 'required|exists:divisions,id',
        ]);

        // Otomatisasi Username: Nama -> nama_bimpps
        $username = strtolower(Str::slug($request->name, '_')) . '_bimpps';

        User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? '123456'), // Default password jika kosong
            'role' => $request->role,
            'division_id' => $request->division_id,
        ]);

        return back()->with('success', "User berhasil ditambahkan dengan username: $username");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:admin,superadmin',
            'division_id' => 'required|exists:divisions,id',
        ]);

        // Username ikut diperbarui jika Nama berubah
        $username = strtolower(Str::slug($request->name, '_')) . '_bimpps';

        $data = [
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'role' => $request->role,
            'division_id' => $request->division_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus diri sendiri!']);
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
