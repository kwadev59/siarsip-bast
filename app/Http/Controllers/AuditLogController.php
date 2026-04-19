<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $logs = AuditLog::with(['user', 'division'])
            ->when($user?->role !== 'superadmin', fn ($query) => $query->where('division_id', $user?->division_id))
            ->latest()
            ->paginate(15);

        return view('audit-logs.index', [
            'logs' => $logs,
        ]);
    }
}
