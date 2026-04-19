<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('master.index', [
            'title' => 'Locations',
            'subtitle' => 'Kelola lokasi penyimpanan fisik dokumen.',
            'records' => Location::with('parent')->latest()->paginate(10),
            'columns' => ['Name', 'Type', 'Parent'],
            'renderRow' => fn (Location $location) => [
                $location->name,
                $location->type,
                $location->parent?->name ?? '-',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'type' => 'required|string',
        ]);
        Location::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);
        return back()->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'type' => 'required|string',
        ]);
        $location->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);
        return back()->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(Location $location)
    {
        // Proteksi Integritas Data
        if ($location->archives()->exists()) {
            return back()->withErrors(['error' => "Lokasi tidak bisa dihapus karena masih digunakan oleh {$location->archives()->count()} arsip."]);
        }

        $location->delete();
        return back()->with('success', 'Lokasi berhasil dihapus!');
    }
}
