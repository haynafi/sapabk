<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentIntakeRequest;
use App\Models\Classroom;
use App\Models\QuizResponse;
use App\Models\Student;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StudentIntakeController extends Controller
{
    public function show(string $unit_code): Response
    {
        // Find classroom by unit_code without school scope (public route)
        $classroom = Classroom::withoutGlobalScopes()
            ->where('unit_code', $unit_code)
            ->with('quizTemplate')
            ->firstOrFail();

        return Inertia::render('Join/Show', [
            'classroom' => $classroom,
            'quizTemplate' => $classroom->quizTemplate,
        ]);
    }

    public function store(StoreStudentIntakeRequest $request, string $unit_code): RedirectResponse
    {
        // Find classroom by unit_code without school scope (public route)
        $classroom = Classroom::withoutGlobalScopes()
            ->where('unit_code', $unit_code)
            ->with('quizTemplate')
            ->firstOrFail();

        DB::transaction(function () use ($request, $classroom) {
            // Handle photo upload with secure randomized filename
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                // Generate a cryptographically secure random filename
                $filename = Str::uuid() . '.' . $extension;
                $photoPath = $file->storeAs('students/photos', $filename, 'public');
            }

            // Create student
            $student = new Student([
                'classroom_id' => $classroom->id,
                'name' => $request->name,
                'nisn' => $request->nisn,
                'photo_path' => $photoPath,
                'biodata' => $request->biodata,
            ]);

            // Manually set school_id since we're bypassing the trait's auto-assignment
            $student->school_id = $classroom->school_id;
            $student->save();

            // Create quiz response if classroom has a quiz template
            if ($classroom->quiz_template_id && $request->has('answers')) {
                QuizResponse::create([
                    'student_id' => $student->id,
                    'quiz_template_id' => $classroom->quiz_template_id,
                    'classroom_id' => $classroom->id,
                    'answers' => $request->answers,
                ]);

                // Log quiz submission
                ActivityLogger::logQuizSubmission(
                    schoolId: $classroom->school_id,
                    studentId: $student->id,
                    studentName: $student->name,
                    classroomId: $classroom->id,
                    classroomName: $classroom->name
                );
            }
        });

        return redirect()->route('join.show', $unit_code)
            ->with('success', 'Registration completed successfully!');
    }
}
