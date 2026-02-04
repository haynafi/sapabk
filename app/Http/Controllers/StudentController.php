<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only see students in their own classrooms
        $classroomQuery = Classroom::select('id', 'name');
        if ($user->isTeacher()) {
            $classroomQuery->where('teacher_id', $user->id);
        }
        $classrooms = $classroomQuery->get();
        $classroomIds = $classrooms->pluck('id');

        $query = Student::with('classroom')
            ->orderBy('name');

        // PRIVACY: Teachers can only see students in their own classrooms
        if ($user->isTeacher()) {
            $query->whereIn('classroom_id', $classroomIds);
        }

        if ($request->filled('classroom_id')) {
            // Ensure teacher can only filter by their own classrooms
            if ($user->isTeacher() && !$classroomIds->contains($request->classroom_id)) {
                abort(403, 'You do not have permission to view students in this classroom.');
            }
            $query->where('classroom_id', $request->classroom_id);
        }

        return Inertia::render('Students/Index', [
            'students' => $query->paginate(20),
            'classrooms' => $classrooms,
            'filters' => $request->only(['classroom_id']),
        ]);
    }

    public function show(Student $student): Response
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only view students in their own classrooms
        if ($user->isTeacher()) {
            $ownsClassroom = Classroom::where('id', $student->classroom_id)
                ->where('teacher_id', $user->id)
                ->exists();

            if (!$ownsClassroom) {
                abort(403, 'You do not have permission to view this student.');
            }
        }

        $student->load(['classroom.quizTemplate', 'quizResponses.quizTemplate']);

        // Log viewing student profile (PDP requirement)
        ActivityLogger::logView($student, $student->name);

        return Inertia::render('Students/Show', [
            'student' => $student,
        ]);
    }
}
