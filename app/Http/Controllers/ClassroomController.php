<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Models\QuizTemplate;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ClassroomController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $query = Classroom::with(['teacher', 'quizTemplate'])
            ->withCount('students')
            ->orderBy('created_at', 'desc');

        // PRIVACY: Teachers can only see their own classrooms
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        return Inertia::render('Classrooms/Index', [
            'classrooms' => $query->paginate(10),
        ]);
    }

    public function create(): Response
    {
        $user = auth()->user();

        // Teachers can only use their own quiz templates
        $quizQuery = QuizTemplate::select('id', 'title');
        if ($user->isTeacher()) {
            $quizQuery->where('teacher_id', $user->id);
        }

        return Inertia::render('Classrooms/Create', [
            'quizTemplates' => $quizQuery->get(),
        ]);
    }

    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        $classroom = Classroom::create([
            'teacher_id' => auth()->id(),
            'name' => $request->name,
            'unit_code' => $this->generateUniqueUnitCode(),
            'quiz_template_id' => $request->quiz_template_id,
        ]);

        ActivityLogger::logCreate($classroom, $classroom->name);

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom created successfully.');
    }

    public function show(Classroom $classroom): Response
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only view their own classrooms
        if ($user->isTeacher() && $classroom->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to view this classroom.');
        }

        return Inertia::render('Classrooms/Show', [
            'classroom' => $classroom->load(['teacher', 'quizTemplate']),
            'students' => $classroom->students()
                ->orderBy('name')
                ->paginate(20),
            'joinUrl' => route('join.show', $classroom->unit_code),
        ]);
    }

    public function edit(Classroom $classroom): Response
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only edit their own classrooms
        if ($user->isTeacher() && $classroom->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to edit this classroom.');
        }

        // Teachers can only use their own quiz templates
        $quizQuery = QuizTemplate::select('id', 'title');
        if ($user->isTeacher()) {
            $quizQuery->where('teacher_id', $user->id);
        }

        return Inertia::render('Classrooms/Edit', [
            'classroom' => $classroom,
            'quizTemplates' => $quizQuery->get(),
        ]);
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only update their own classrooms
        if ($user->isTeacher() && $classroom->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to update this classroom.');
        }

        $classroom->update([
            'name' => $request->name,
            'quiz_template_id' => $request->quiz_template_id,
        ]);

        ActivityLogger::logUpdate($classroom, $classroom->name);

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only delete their own classrooms
        if ($user->isTeacher() && $classroom->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to delete this classroom.');
        }

        ActivityLogger::logDelete($classroom, $classroom->name);

        $classroom->delete();

        return redirect()->route('classrooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }

    protected function generateUniqueUnitCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Classroom::withoutGlobalScopes()->where('unit_code', $code)->exists());

        return $code;
    }
}
