<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\AuditLog;
use App\Models\BorrowingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingLogController extends Controller
{
    public function index()
    {
        $borrowingLogs = BorrowingLog::with(['archive.category', 'archive.division'])
            ->latest()
            ->paginate(10);

        return view('borrowings.index', [
            'borrowingLogs' => $borrowingLogs,
        ]);
    }

    public function borrow(Request $request, Archive $archive)
    {
        $validated = $request->validate([
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrower_division' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        abort_if($archive->status === 'borrowed', 422, 'Arsip sedang dipinjam.');

        $user = Auth::user();

        DB::transaction(function () use ($archive, $validated, $request, $user) {
            BorrowingLog::create([
                'archive_id' => $archive->id,
                'borrower_name' => $validated['borrower_name'],
                'borrower_division' => $validated['borrower_division'] ?? null,
                'borrowed_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            $archive->update(['status' => 'borrowed']);

            AuditLog::create([
                'user_id' => $user?->id,
                'division_id' => $archive->division_id,
                'action' => 'borrow',
                'table_name' => 'borrowing_logs',
                'record_id' => $archive->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'archive_uuid' => $archive->uuid,
                    'borrower_name' => $validated['borrower_name'],
                ],
            ]);
        });

        return back()->with('success', 'Arsip berhasil dipinjamkan.');
    }

    public function returnArchive(Request $request, BorrowingLog $borrowingLog)
    {
        abort_if($borrowingLog->returned_at !== null, 422, 'Arsip sudah dikembalikan.');

        $user = Auth::user();

        DB::transaction(function () use ($borrowingLog, $request, $user) {
            $borrowingLog->update([
                'returned_at' => now(),
            ]);

            $borrowingLog->archive->update(['status' => 'active']);

            AuditLog::create([
                'user_id' => $user?->id,
                'division_id' => $borrowingLog->archive->division_id,
                'action' => 'return',
                'table_name' => 'borrowing_logs',
                'record_id' => $borrowingLog->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'archive_uuid' => $borrowingLog->archive->uuid,
                    'borrower_name' => $borrowingLog->borrower_name,
                ],
            ]);
        });

        return back()->with('success', 'Arsip berhasil dikembalikan.');
    }
}
