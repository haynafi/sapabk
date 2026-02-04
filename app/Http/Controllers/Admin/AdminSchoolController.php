<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminSchoolController extends Controller
{
    public function index(Request $request): Response
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

    public function create(): Response
    {
        return Inertia::render('Admin/Schools/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:schools,slug|alpha_dash',
        ]);

        School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function edit(School $school): Response
    {
        return Inertia::render('Admin/Schools/Edit', [
            'school' => $school->loadCount(['users', 'classrooms', 'students']),
        ]);
    }

    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('schools')->ignore($school->id)],
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school): RedirectResponse
    {
        // Check if school has any users
        if ($school->users()->count() > 0) {
            return redirect()->route('admin.schools.index')
                ->with('error', 'Cannot delete school with existing teachers. Please reassign or delete teachers first.');
        }

        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }
}
