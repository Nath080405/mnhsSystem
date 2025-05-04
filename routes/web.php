<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\student\StudentController as StudentDashboardController;
use App\Http\Controllers\Teacher\TeacherController as TeacherDashboardController;



// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // Subject Management Routes
        Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects.index');
        Route::get('/subjects/create', [SubjectController::class, 'create'])->name('admin.subjects.create');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
        Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
        Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
        Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('admin.subjects.show');

        // Teacher Management Routes
        Route::get('/teachers', [TeacherController::class, 'index'])->name('admin.teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store');
        Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
        Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('admin.teachers.update');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy');
        Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('admin.teachers.show');

        // Student Management Routes
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('admin.students.show');
    });

    // Teacher Routes
    Route::middleware(['role:teacher'])->prefix('teacher')->group(function () {
        Route::get('/dashboard', function () {
            return view('teachers.dashboard');
        })->name('teachers.dashboard');

        Route::get('/student', [TeacherDashboardController::class, 'student'])->name('teachers.student');
        Route::get('/student/add', [TeacherDashboardController::class, 'addStudent'])->name('teachers.students.addStudents');
        Route::get('/student/index', [TeacherController::class, 'indexStudent'])->name('teachers.student.index');
        Route::get('/student/create', [TeacherDashboardController::class, 'createStudent'])->name('teachers.student.create');

        Route::get('/subject', [TeacherDashboardController::class, 'subjectIndex'])->name('teachers.subject');
        Route::post('/subject', [TeacherDashboardController::class, 'storeSubject'])->name('teachers.subject.index');

        Route::get('/grade', [TeacherDashboardController::class, 'gradeIndex'])->name('teachers.grade');

        // Remove these conflicting routes
        // Route::get('/event', [TeacherDashboardController::class, 'eventIndex'])->name('teachers.event');
        // Route::get('/event', [TeacherController::class, 'eventIndex'])->name('teachers.event');

        // Keep only these for events
        Route::get('/teacher/event', [TeacherDashboardController::class, 'event'])->name('teachers.event.index');
        Route::get('/event', [TeacherDashboardController::class, 'event'])->name('teachers.event');
        Route::get('/event/create', [TeacherDashboardController::class, 'create'])->name('teachers.event.create');
        Route::post('/event', [TeacherDashboardController::class, 'store'])->name('teachers.event.store');
    }); //ayaw og lapas dre


    // Student Routes
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/gradebook', [StudentDashboardController::class, 'gradebook'])->name('student.gradebook');
        Route::get('/gradebook/pdf', [StudentDashboardController::class, 'downloadGradebookPDF'])->name('student.gradebook.pdf');
        Route::get('/events', [StudentDashboardController::class, 'events'])->name('student.events');
        Route::get('/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
        Route::get('/assignments', [StudentDashboardController::class, 'assignments'])->name('student.assignments');
    });

});
