<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DivisionController extends Controller
{
    public function index()
    {
        return view('master.index', [
            'title' => 'Divisions',
            'subtitle' => 'Kelola data divisi kerja dalam perusahaan.',
            'records' => Division::latest()->paginate(10),
            'columns' => ['Code', 'Name'],
            'renderRow' => fn (Division $division) => [$division->code, $division->name],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:divisions,name']);
        Division::create([
            'name' => $request->name,
            'code' => strtoupper(Str::slug($request->name)),
        ]);
        return back()->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function update(Request $request, Division $division)
    {
        $request->validate(['name' => 'required|string|max:255|unique:divisions,name,' . $division->id]);
        $division->update([
            'name' => $request->name,
            'code' => strtoupper(Str::slug($request->name)),
        ]);
        return back()->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy(Division $division)
    {
        // Proteksi Integritas Data
        if ($division->archives()->exists()) {
            return back()->withErrors(['error' => "Divisi tidak bisa dihapus karena masih digunakan oleh {$division->archives()->count()} arsip."]);
        }

        $division->delete();
        return back()->with('success', 'Divisi berhasil dihapus!');
    }
}
