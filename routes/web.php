<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/evaluation-form', [EvaluationFormController::class, 'index'])->name('evaluation.form');

Route::middleware(['auth'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', App\Livewire\Pages\Admin\Dashboard\Index::class)->name('dashboard.index');
        Route::get('/subjects', App\Livewire\Pages\Admin\Subjects\Index::class)->name('subjects.index');
        Route::get('/courses', App\Livewire\Pages\Admin\Courses\Index::class)->name('courses.index');
        Route::post('/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');
        Route::get('/courses/{course}/semesters', [CourseController::class, 'showSemesters'])->name('courses.showSemesters');
        Route::post('/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');
        Route::get('/departments', App\Livewire\Pages\Admin\Departments\Index::class)->name('departments.index');

        Route::get('/school-years', App\Livewire\Pages\Admin\SchoolYears\Index::class)->name('school-years.index');
        Route::get('/sections', App\Livewire\Pages\Admin\Sections\Index::class)->name('sections.index');
        Route::get('/students', App\Livewire\Pages\Admin\Students\Index::class)->name('students.index');
        Route::get('/teachers', App\Livewire\Pages\Admin\Teachers\Index::class)->name('teachers.index');
        Route::get('/accounts', App\Livewire\Pages\Admin\Teachers\Index::class)->name('accounts.index');
    });

require __DIR__ . '/auth.php';
