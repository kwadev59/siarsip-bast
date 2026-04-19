<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Division;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $archives = Archive::query()
            ->with(['category', 'division', 'location', 'creator'])
            ->when($user?->role !== 'superadmin', fn ($query) => $query->where('division_id', $user?->division_id))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('document_number', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('division_id') && $user?->role === 'superadmin', fn ($query) => $query->where('division_id', $request->integer('division_id')))
            ->when($request->filled('location_id'), fn ($query) => $query->where('location_id', $request->integer('location_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('archives.index', [
            'archives' => $archives,
            'categories' => Category::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('archives.create', [
            'categories' => Category::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $user = Auth::user();
        $storedPath = $request->file('file')->store('archives', 'public');
        $data = Arr::except($validated, ['file']);
        $division = Division::query()->findOrFail($validated['division_id']);

        $archive = DB::transaction(function () use ($data, $division, $storedPath, $request, $user) {
            $archive = Archive::create([
                ...$data,
                'document_number' => $this->generateDocumentNumber($division),
                'file_path' => $storedPath,
                'file_name_original' => $request->file('file')->getClientOriginalName(),
                'file_mime_type' => $request->file('file')->getClientMimeType(),
                'file_size' => $request->file('file')->getSize(),
                'document_date' => now()->toDateString(),
                'status' => 'active',
                'created_by' => $user?->id,
            ]);

            AuditLog::create([
                'user_id' => $user?->id,
                'division_id' => $archive->division_id,
                'action' => 'create',
                'table_name' => 'archives',
                'record_id' => $archive->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'document_number' => $archive->document_number,
                ],
            ]);

            return $archive;
        });

        return redirect()->route('archives.show', $archive)->with('success', 'Arsip berhasil dibuat.');
    }

    public function show(Archive $archive)
    {
        $archive->load(['category', 'division', 'location', 'creator', 'borrowingLogs' => fn ($query) => $query->latest()]);

        return view('archives.show', [
            'archive' => $archive,
            'downloadUrl' => $archive->file_path ? URL::temporarySignedRoute('archives.download', now()->addMinutes(15), ['archive' => $archive]) : null,
        ]);
    }

    public function edit(Archive $archive)
    {
        return view('archives.edit', [
            'archive' => $archive,
            'categories' => Category::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Archive $archive)
    {
        $validated = $request->validate([
            'document_number' => ['required', 'string', 'max:100', 'unique:archives,document_number,' . $archive->id],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'document_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status' => ['required', 'in:active,borrowed,archived'],
        ]);

        $user = Auth::user();
        $data = Arr::except($validated, ['file']);

        DB::transaction(function () use ($data, $archive, $request, $user) {
            if ($request->hasFile('file')) {
                if ($archive->file_path) {
                    Storage::disk('public')->delete($archive->file_path);
                }

                $archive->file_path = $request->file('file')->store('archives', 'public');
                $archive->file_name_original = $request->file('file')->getClientOriginalName();
                $archive->file_mime_type = $request->file('file')->getClientMimeType();
                $archive->file_size = $request->file('file')->getSize();
            }

            $archive->fill($data);
            $archive->save();

            AuditLog::create([
                'user_id' => $user?->id,
                'division_id' => $archive->division_id,
                'action' => 'update',
                'table_name' => 'archives',
                'record_id' => $archive->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'document_number' => $archive->document_number,
                ],
            ]);
        });

        return redirect()->route('archives.show', $archive)->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Request $request, Archive $archive)
    {
        $user = Auth::user();

        DB::transaction(function () use ($archive, $request, $user) {
            $archive->delete();

            AuditLog::create([
                'user_id' => $user?->id,
                'division_id' => $archive->division_id,
                'action' => 'delete',
                'table_name' => 'archives',
                'record_id' => $archive->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'document_number' => $archive->document_number,
                ],
            ]);
        });

        return redirect()->route('archives.index')->with('success', 'Arsip dipindahkan ke sampah.');
    }

    public function download(Archive $archive)
    {
        abort_unless($archive->file_path && Storage::disk('public')->exists($archive->file_path), 404);

        return Storage::disk('public')->download(
            $archive->file_path,
            $archive->file_name_original ?: basename($archive->file_path)
        );
    }

    private function generateDocumentNumber(Division $division): string
    {
        $divisionCode = Str::of($division->name)
            ->upper()
            ->replaceMatches('/[^A-Z0-9]+/', '-')
            ->trim('-')
            ->toString();

        do {
            $documentNumber = sprintf(
                '%04d-%s-PT.BIM-PPS-%s-%s',
                random_int(1000, 9999),
                $divisionCode,
                now()->format('m'),
                now()->format('Y')
            );
        } while (Archive::where('document_number', $documentNumber)->exists());

        return $documentNumber;
    }
}
