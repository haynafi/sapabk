<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\QuizTemplate;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $user = auth()->user();

        // Super admins get redirected to admin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // PRIVACY: Teachers only see stats for their own resources
        $classroomIds = Classroom::where('teacher_id', $user->id)->pluck('id');

        return Inertia::render('Dashboard', [
            'stats' => [
                'classrooms' => Classroom::where('teacher_id', $user->id)->count(),
                'students' => Student::whereIn('classroom_id', $classroomIds)->count(),
                'quizTemplates' => QuizTemplate::where('teacher_id', $user->id)->count(),
            ],
        ]);
    }
}
