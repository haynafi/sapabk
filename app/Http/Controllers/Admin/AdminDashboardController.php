<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\QuizTemplate;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with system-wide statistics.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'schools' => School::count(),
                'teachers' => User::where('role', User::ROLE_TEACHER)->count(),
                'classrooms' => Classroom::count(),
                'students' => Student::count(),
                'quizTemplates' => QuizTemplate::count(),
            ],
            'recentSchools' => School::withCount(['users', 'classrooms', 'students'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recentTeachers' => User::with('school')
                ->where('role', User::ROLE_TEACHER)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * List all schools.
     */
    public function schools(Request $request): Response
    {
        $query = School::withCount(['users', 'classrooms', 'students'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Admin/Schools/Index', [
            'schools' => $query->paginate(15),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * List all teachers.
     */
    public function teachers(Request $request): Response
    {
        $query = User::with('school')
            ->withCount(['classrooms', 'quizTemplates'])
            ->where('role', User::ROLE_TEACHER)
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        return Inertia::render('Admin/Teachers/Index', [
            'teachers' => $query->paginate(15),
            'schools' => School::select('id', 'name')->orderBy('name')->get(),
            'filters' => $request->only(['search', 'school_id']),
        ]);
    }

    /**
     * System-wide audit logs (all users, all schools).
     */
    public function auditLogs(Request $request): Response
    {
        $query = AuditLog::with(['user', 'user.school'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return Inertia::render('Admin/AuditLogs/Index', [
            'logs' => $query->paginate(50),
            'filters' => $request->only(['action', 'subject_type', 'user_id', 'date_from', 'date_to']),
            'teachers' => User::where('role', User::ROLE_TEACHER)
                ->select('id', 'name', 'nip')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
