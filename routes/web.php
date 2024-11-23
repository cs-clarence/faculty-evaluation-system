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

//Route Subject
Route::get('/admin/subjects', App\Livewire\Pages\Admin\Subjects\Index::class)->name('subjects.index');

//Route Course
Route::get('/admin/courses', App\Livewire\Pages\Admin\Courses\Index::class)->name('courses.index');

//Route Course Semester

// Route to store a new semester for a specific course
Route::post('/admin/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');

// Route to show the semesters for a specific course
Route::get('/admin/courses/{course}/semesters', [CourseController::class, 'showSemesters'])->name('courses.showSemesters');
// Route to store a new semester for a specific course
Route::post('/admin/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');

//Route Evaluation Form
Route::get('/evaluation-form', [EvaluationFormController::class, 'index'])->name('evaluation.form');

//Route for department
Route::get('/admin/departments', App\Livewire\Pages\Admin\Departments\Index::class)->name('departments.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/school-years', App\Livewire\Pages\Admin\SchoolYears\Index::class)->name('school-years.index');

require __DIR__ . '/auth.php';
