<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizTemplateRequest;
use App\Http\Requests\UpdateQuizTemplateRequest;
use App\Models\QuizTemplate;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class QuizTemplateController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $query = QuizTemplate::with('teacher')
            ->orderBy('created_at', 'desc');

        // PRIVACY: Teachers can only see their own quiz templates
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        return Inertia::render('QuizTemplates/Index', [
            'quizTemplates' => $query->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('QuizTemplates/Create');
    }

    public function store(StoreQuizTemplateRequest $request): RedirectResponse
    {
        $quizTemplate = QuizTemplate::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'questions' => $request->questions,
        ]);

        ActivityLogger::logCreate($quizTemplate, $quizTemplate->title);

        return redirect()->route('quiz-templates.index')
            ->with('success', 'Quiz template created successfully.');
    }

    public function edit(QuizTemplate $quizTemplate): Response
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only edit their own quiz templates
        if ($user->isTeacher() && $quizTemplate->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to edit this quiz template.');
        }

        return Inertia::render('QuizTemplates/Edit', [
            'quizTemplate' => $quizTemplate,
        ]);
    }

    public function update(UpdateQuizTemplateRequest $request, QuizTemplate $quizTemplate): RedirectResponse
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only update their own quiz templates
        if ($user->isTeacher() && $quizTemplate->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to update this quiz template.');
        }

        $quizTemplate->update([
            'title' => $request->title,
            'questions' => $request->questions,
        ]);

        ActivityLogger::logUpdate($quizTemplate, $quizTemplate->title);

        return redirect()->route('quiz-templates.index')
            ->with('success', 'Quiz template updated successfully.');
    }

    public function destroy(QuizTemplate $quizTemplate): RedirectResponse
    {
        $user = auth()->user();

        // PRIVACY: Teachers can only delete their own quiz templates
        if ($user->isTeacher() && $quizTemplate->teacher_id !== $user->id) {
            abort(403, 'You do not have permission to delete this quiz template.');
        }

        ActivityLogger::logDelete($quizTemplate, $quizTemplate->title);

        $quizTemplate->delete();

        return redirect()->route('quiz-templates.index')
            ->with('success', 'Quiz template deleted successfully.');
    }
}
