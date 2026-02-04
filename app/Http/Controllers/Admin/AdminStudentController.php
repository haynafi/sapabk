<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminStudentController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Student::with(['classroom.teacher', 'classroom.school'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('school_id')) {
            $query->whereHas('classroom', function ($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        return Inertia::render('Admin/Students/Index', [
            'students' => $query->paginate(20),
            'schools' => School::select('id', 'name')->orderBy('name')->get(),
            'classrooms' => Classroom::with(['teacher:id,name', 'school:id,name'])
                ->select('id', 'name', 'teacher_id', 'school_id')
                ->orderBy('name')
                ->get(),
            'filters' => $request->only(['search', 'school_id', 'classroom_id']),
        ]);
    }

    public function show(Student $student): Response
    {
        $student->load(['classroom.teacher', 'classroom.school', 'classroom.quizTemplate', 'quizResponses.quizTemplate']);

        // Log viewing student profile (PDP requirement)
        ActivityLogger::logView($student, $student->name);

        return Inertia::render('Admin/Students/Show', [
            'student' => $student,
        ]);
    }

    public function destroy(Student $student): RedirectResponse
    {
        ActivityLogger::logDelete($student, $student->name);

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
