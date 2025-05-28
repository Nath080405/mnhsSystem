<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Student\StudentController as StudentDashboardController;
use App\Http\Controllers\Teacher\TeacherController as TeacherDashboardController;
use App\Http\Controllers\Teacher\EventController as TeacherEventController;

// Authentication Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Routes protected by authentication
Route::middleware('auth')->group(function () {

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // Events
        Route::resource('events', EventController::class)->names('admin.events');

        // Sections
        Route::resource('sections', SectionController::class)->names('admin.sections');

        // Subjects
        Route::resource('subjects', SubjectController::class)->names('admin.subjects');

        // Teachers
        Route::resource('teachers', TeacherController::class)->names('admin.teachers');

        // Students
        Route::resource('students', StudentController::class)->names('admin.students');
    });

    // Teacher routes
    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', function () {
            return view('teachers.dashboard');
        })->name('teachers.dashboard');

        // Students management
        Route::resource('students', TeacherDashboardController::class)->except(['show', 'index'])->names('teachers.student');
        Route::get('/students', [TeacherDashboardController::class, 'indexStudent'])->name('teachers.student.index');
        Route::get('/students/search', [TeacherDashboardController::class, 'searchStudent'])->name('teachers.student.search');
        Route::get('/students/{id}', [TeacherDashboardController::class, 'show'])->name('teachers.student.show');

        // Student Grades
        Route::get('/student/grades', [TeacherDashboardController::class, 'indexStudentGrade'])->name('teachers.student.grade.index');
        Route::post('/student/grades', [TeacherDashboardController::class, 'storeGrades'])->name('teachers.student.grade.store');
        Route::get('/student/grades/search', [TeacherDashboardController::class, 'search'])->name('teachers.student.grade.search');

        // Subjects management
        Route::resource('subjects', TeacherDashboardController::class)->except(['show', 'index'])->names('teachers.subject');
        Route::get('/subjects', [TeacherDashboardController::class, 'subjectIndex'])->name('teachers.subject.index');
        Route::get('/subjects/{id}', [TeacherDashboardController::class, 'show'])->name('teachers.subject.show');

        // Events for teachers
        Route::get('/events', [TeacherEventController::class, 'index'])->name('teachers.event.index');
        Route::post('/events/preview', [TeacherEventController::class, 'preview'])->name('teachers.event.preview');
        Route::get('/events/{event}', [TeacherEventController::class, 'showEvent'])->name('teachers.event.show');

        // Profile
        Route::get('/profile', [TeacherDashboardController::class, 'profile'])->name('teachers.profile');
        Route::put('/profile', [TeacherDashboardController::class, 'updateProfile'])->name('teachers.profile.update');

        // CSV import and templates
        Route::get('/student/template', [TeacherDashboardController::class, 'downloadTemplate'])->name('teachers.student.template');
        Route::post('/student/import', [TeacherDashboardController::class, 'importStudents'])->name('teachers.student.import');
    });

    // Student routes
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/gradebook', [StudentDashboardController::class, 'gradebook'])->name('student.gradebook');
        Route::get('/gradebook/pdf', [StudentDashboardController::class, 'downloadGradebookPDF'])->name('student.gradebook.pdf');
        Route::get('/events', [StudentDashboardController::class, 'events'])->name('student.events');
        Route::get('/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
        Route::get('/assignments', [StudentDashboardController::class, 'assignments'])->name('student.assignments');
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    });
});
