<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSchoolController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizTemplateController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentIntakeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root URL: redirect based on authentication status and role
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Public student intake (no auth required)
Route::get('/join/{unit_code}', [StudentIntakeController::class, 'show'])->name('join.show');
Route::post('/join/{unit_code}', [StudentIntakeController::class, 'store'])->name('join.store');

// Auth routes (password change - accessible even with must_change_password)
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.update');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin routes
Route::middleware(['auth', 'verified', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Schools CRUD
    Route::resource('schools', AdminSchoolController::class);

    // Teachers CRUD
    Route::resource('teachers', AdminTeacherController::class);

    // Students (view all, show, delete)
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
    Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');

    // Audit Logs (system-wide)
    Route::get('/audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('audit-logs');
});

// Protected routes (teachers) - CheckPasswordChange middleware applies
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quiz Templates
    Route::resource('quiz-templates', QuizTemplateController::class)->except(['show']);

    // Classrooms
    Route::resource('classrooms', ClassroomController::class);

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Audit Logs (PDP Compliance) - Teachers see only their own logs
    Route::get('/activity-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

require __DIR__.'/auth.php';
