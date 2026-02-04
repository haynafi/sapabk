<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(Request $request): Response
    {
        $user = auth()->user();

        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // PRIVACY: Teachers can only see their own audit logs
        if ($user->isTeacher()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return Inertia::render('AuditLogs/Index', [
            'logs' => $query->paginate(50),
            'filters' => $request->only(['action', 'subject_type', 'date_from', 'date_to']),
        ]);
    }
}
