<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminTeacherController extends Controller
{
    public function index(Request $request): Response
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

    public function create(): Response
    {
        return Inertia::render('Admin/Teachers/Create', [
            'schools' => School::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'must_change_password' => 'boolean',
        ]);

        $teacher = new User();
        $teacher->name = $validated['name'];
        $teacher->nip = $validated['nip'];
        $teacher->email = $validated['email'] ?? null;
        $teacher->password = Hash::make($validated['password']);
        $teacher->school_id = $validated['school_id'];
        $teacher->role = User::ROLE_TEACHER;
        $teacher->must_change_password = $validated['must_change_password'] ?? true;
        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher): Response
    {
        // Ensure we're editing a teacher
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        return Inertia::render('Admin/Teachers/Edit', [
            'teacher' => $teacher->load('school')->loadCount(['classrooms', 'quizTemplates']),
            'schools' => School::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($teacher->id)],
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'must_change_password' => 'boolean',
        ]);

        $teacher->name = $validated['name'];
        $teacher->nip = $validated['nip'];
        $teacher->email = $validated['email'] ?? null;
        $teacher->school_id = $validated['school_id'];

        if (!empty($validated['password'])) {
            $teacher->password = Hash::make($validated['password']);
        }

        if (isset($validated['must_change_password'])) {
            $teacher->must_change_password = $validated['must_change_password'];
        }

        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher): RedirectResponse
    {
        if (!$teacher->isTeacher()) {
            abort(404);
        }

        // Check if teacher has classrooms
        if ($teacher->classrooms()->count() > 0) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Cannot delete teacher with existing classrooms. Please delete classrooms first.');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
