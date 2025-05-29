<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\student\StudentController as StudentDashboardController;
use App\Http\Controllers\Teacher\TeacherController as TeacherDashboardController;
use App\Http\Controllers\Teacher\EventController as TeacherEventController;
use App\Http\Controllers\Admin\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Models\User;
use Illuminate\Support\Facades\Schema;


// Authentication Routes
Route::get('', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('admin.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // Event Management Routes
        Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/events', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('admin.events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');
        Route::get('/events/{id}', [EventController::class, 'show'])->name('admin.events.show');

        // Section Management Routes
        Route::get('/sections', [SectionController::class, 'index'])->name('admin.sections.index');
        Route::get('/sections/create', [SectionController::class, 'create'])->name('admin.sections.create');
        Route::post('/sections', [SectionController::class, 'store'])->name('admin.sections.store');
        Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('admin.sections.edit');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('admin.sections.destroy');

        // Subject Management Routes
        Route::prefix('subjects')->name('admin.subjects.')->group(function () {
            Route::get('/', [SubjectController::class, 'index'])->name('index');
            Route::get('/create', [SubjectController::class, 'create'])->name('create');
            Route::post('/', [SubjectController::class, 'store'])->name('store');
            Route::get('/plan', [SubjectController::class, 'plan'])->name('plan');
            Route::get('/grade/{grade}', [SubjectController::class, 'gradeSubjects'])->name('grade');
            Route::get('/label/{id}/subjects', [SubjectController::class, 'labelSubjects'])->name('label.subjects');
            Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
            Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
            Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
            Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        });

        // Teacher Management Routes
        Route::get('/teachers', [TeacherController::class, 'index'])->name('admin.teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store');

        // CSV Import and Template Download for Teachers
        Route::get('/teachers/template', [TeacherController::class, 'downloadTemplate'])->name('admin.teachers.template');
        Route::post('/teachers/import', [TeacherController::class, 'importTeachers'])->name('admin.teachers.import');

        Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('admin.teachers.show');
        Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
        Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('admin.teachers.update');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy');

        // Student Management Routes
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('admin.students.show');

        // CSV Import and Template Download
        Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('admin.students.template');
        Route::post('/students/import', [StudentController::class, 'importStudents'])->name('admin.students.import');
    });

    // Teacher Routes
    Route::middleware(['role:teacher'])->prefix('teacher')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('teachers.dashboard');
        })->name('teachers.dashboard');

        // Student Management Routes
        Route::get('/students', [\App\Http\Controllers\Teacher\TeacherController::class, 'indexStudent'])->name('teachers.student.index');
        Route::get('/students/create', [\App\Http\Controllers\Teacher\TeacherController::class, 'createStudent'])->name('teachers.student.create');
        Route::post('/students', [\App\Http\Controllers\Teacher\TeacherController::class, 'storeStudent'])->name('teachers.student.store');
        Route::get('/students/{id}/edit', [\App\Http\Controllers\Teacher\TeacherController::class, 'edit'])->name('teachers.student.edit');
        Route::put('/students/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'update'])->name('teachers.student.update');
        Route::delete('/students/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'destroy'])->name('teachers.student.destroy');
        Route::get('/students/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'show'])->name('teachers.student.show');
        Route::get('/students/search', [\App\Http\Controllers\Teacher\TeacherController::class, 'searchStudent'])->name('teachers.student.search');

        // Grade Management Routes
        Route::get('/student/grades', [\App\Http\Controllers\Teacher\TeacherController::class, 'indexStudentGrade'])->name('teachers.student.grade.index');
        Route::post('/student/grades', [\App\Http\Controllers\Teacher\TeacherController::class, 'storeGrades'])->name('teachers.student.grade.store');
        Route::get('/student/grades/search', [\App\Http\Controllers\Teacher\TeacherController::class, 'search'])->name('teachers.student.grade.search');

        // Subject Management Routes
        Route::get('/subjects', [\App\Http\Controllers\Teacher\TeacherController::class, 'subjectIndex'])->name('teachers.subject.index');
        Route::get('/subjects/create', [\App\Http\Controllers\Teacher\TeacherController::class, 'create'])->name('teachers.subject.create');
        Route::post('/subjects', [\App\Http\Controllers\Teacher\TeacherController::class, 'store'])->name('teachers.subject.store');
        Route::get('/subjects/{id}/edit', [\App\Http\Controllers\Teacher\TeacherController::class, 'edit'])->name('teachers.subject.edit');
        Route::put('/subjects/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'update'])->name('teachers.subject.update');
        Route::delete('/subjects/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'destroy'])->name('teachers.subject.destroy');
        Route::get('/subjects/{id}', [\App\Http\Controllers\Teacher\TeacherController::class, 'show'])->name('teachers.subject.show');

        // Event Management Routes
        Route::get('/events', [\App\Http\Controllers\Teacher\TeacherController::class, 'event'])->name('teachers.event.index');
        Route::post('/events/preview', [\App\Http\Controllers\Teacher\TeacherController::class, 'preview'])->name('teachers.event.preview');

        // Profile Routes
        Route::get('/profile', [\App\Http\Controllers\Teacher\TeacherController::class, 'profile'])->name('teachers.profile');
        Route::put('/profile', [\App\Http\Controllers\Teacher\TeacherController::class, 'updateProfile'])->name('teachers.profile.update');

        // CSV Import and Template Download
        Route::get('/student/template', [\App\Http\Controllers\Teacher\TeacherController::class, 'downloadTemplate'])->name('teachers.student.template');
        Route::post('/student/import', [\App\Http\Controllers\Teacher\TeacherController::class, 'importStudents'])->name('teachers.student.import');
    });


    // Student Routes
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/gradebook', [StudentDashboardController::class, 'gradebook'])->name('student.gradebook');
        Route::get('/gradebook/pdf', [StudentDashboardController::class, 'downloadGradebookPDF'])->name('student.gradebook.pdf');
        Route::get('/events', [StudentDashboardController::class, 'events'])->name('student.events');
        Route::get('/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
        Route::get('/assignments', [StudentDashboardController::class, 'assignments'])->name('student.assignments');
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
        Route::put('/student/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    });

});
