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
        
        //students
        Route::get('/student', [TeacherDashboardController::class, 'student'])->name('teachers.student');
        Route::get('/student/add', [TeacherDashboardController::class, 'addStudent'])->name('teachers.student.addStudents');
        Route::get('/student/index', [TeacherDashboardController::class, 'indexStudent'])->name('teachers.student.index');
        Route::post('/student', [TeacherDashboardController::class, 'storeStudent'])->name('teachers.student.store');
        Route::get('/student/create', [TeacherDashboardController::class, 'createStudent'])->name('teachers.student.create');
        Route::get('/teacher/student/search', [TeacherDashboardController::class, 'searchStudent'])->name('teachers.student.search');

      // Subject Management 
Route::get('/subjects', [TeacherDashboardController::class, 'index'])->name('teachers.subject');

Route::get('/subject', [TeacherDashboardController::class, 'index'])->name('teachers.subject.index');
Route::get('/subject/create', [TeacherDashboardController::class, 'create'])->name('teachers.subject.create');
Route::get('/subject/{subject}', [TeacherDashboardController::class, 'show'])->name('teachers.subject.show'); // ✅ fixed this line
Route::post('/subject', [TeacherDashboardController::class, 'store'])->name('teachers.subject.store');
Route::put('/teacher/subject/{id}', [TeacherDashboardController::class, 'update'])->name('teachers.subject.update');
Route::get('/teacher/subject/{id}/edit', [TeacherDashboardController::class, 'edit'])->name('teachers.subject.edit');
Route::delete('/subject/{id}', [TeacherDashboardController::class, 'destroy'])->name('teachers.subject.destroy');

    
        // Grade Management Routes
        Route::get('/grade', [TeacherDashboardController::class, 'gradeIndex'])->name('teachers.grade');
            Route::get('index', [TeacherController::class, 'indexGrade'])->name('teacher.grade.index');//no code
            Route::get('create', [TeacherController::class, 'createGrade'])->name('teacher.grade.create');//nocode
            Route::post('store', [TeacherController::class, 'storeGrade'])->name('teacher.grade.store');//no code
            Route::get('{id}/edit', [TeacherController::class, 'editGrade'])->name('teacher.grade.edit');//
            Route::put('{id}/update', [TeacherController::class, 'updateGrade'])->name('teacher.grade.update');//
    
        // Keep only these for events

    // Events Routes
    Route::get('/teacher/event', [TeacherDashboardController::class, 'index'])->name('teachers.event.index');
    Route::get('/event', [TeacherDashboardController::class, 'event'])->name('teachers.event');
    Route::get('/event/create', [TeacherDashboardController::class, 'create'])->name('teachers.event.create');
    Route::post('/event', [TeacherDashboardController::class, 'store'])->name('teachers.event.store');
    Route::post('/event/preview', [TeacherDashboardController::class, 'preview'])->name('teachers.event.preview');
    
    // Add the edit route here:
    Route::get('/event/{id}/edit', [TeacherDashboardController::class, 'edit'])->name('teachers.event.edit'); // This is your missing route
    Route::put('/event/{id}', [TeacherDashboardController::class, 'update'])->name('teachers.event.update'); // To update event after editing
    Route::delete('/event/{id}', [TeacherDashboardController::class, 'destroy'])->name('teachers.event.destroy');


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
