<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\AuditLog;
use App\Models\BorrowingLog;
use App\Models\Category;
use App\Models\Division;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $archiveQuery = Archive::query();
        $borrowingQuery = BorrowingLog::query();
        $auditQuery = AuditLog::query();

        if ($user?->role !== 'superadmin') {
            $archiveQuery->where('division_id', $user?->division_id);
            $borrowingQuery->whereHas('archive', fn ($query) => $query->where('division_id', $user?->division_id));
            $auditQuery->where('division_id', $user?->division_id);
        }

        return view('dashboard', [
            'stats' => [
                'archives' => (clone $archiveQuery)->count(),
                'active_archives' => (clone $archiveQuery)->where('status', 'active')->count(),
                'borrowed_archives' => (clone $archiveQuery)->where('status', 'borrowed')->count(),
                'archived_archives' => (clone $archiveQuery)->where('status', 'archived')->count(),
                'borrowings' => $borrowingQuery->count(),
                'overdue_borrowings' => BorrowingLog::query()
                    ->whereNull('returned_at')
                    ->where('borrowed_at', '<', now()->subDays(7))
                    ->when($user?->role !== 'superadmin', function ($query) use ($user) {
                        $query->whereHas('archive', fn ($archive) => $archive->where('division_id', $user?->division_id));
                    })
                    ->count(),
                'divisions' => Division::count(),
                'categories' => Category::count(),
                'locations' => Location::count(),
                'audit_logs' => $auditQuery->count(),
            ],
            'latest_archives' => (clone $archiveQuery)->with(['category', 'division', 'location', 'creator'])
                ->latest()
                ->limit(5)
                ->get(),
            'latest_audit_logs' => (clone $auditQuery)->latest()->limit(5)->get(),
            'categories' => Category::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ]);
    }
}
