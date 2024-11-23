<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SubjectController;
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
Route::get('/admin/subject', [SubjectController::class, 'index'])->name('subjects.index');
Route::post('/admin/subjects/store', [SubjectController::class, 'store'])->name('subjects.store');

//Route Course
Route::get('/admin/courses', [CourseController::class, 'index'])->name('courses.index');
Route::post('/admin/courses', [CourseController::class, 'store'])->name('courses.store');

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
Route::get('/admin/departments', [CourseController::class, 'department_index'])->name('departments.index');
Route::post('/admin/departments', [CourseController::class, 'department_store'])->name('department.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/school-years/create', [SchoolYearController::class, 'create'])->name('school-years.create');
Route::post('/admin/school-years/store', [SchoolYearController::class, 'store'])->name('school-years.store');
Route::get('/admin/school-years', [SchoolYearController::class, 'index'])->name('school-years.index');
Route::get('/admin/school-years/{schoolYear}', [SchoolYearController::class, 'show'])->name('school-years.show');
Route::get('/admin/school-years/{schoolYear}/edit', [SchoolYearController::class, 'edit'])->name('school-years.edit');
Route::post('/admin/school-years/{schoolYear}/update', [SchoolYearController::class, 'update'])->name('school-years.update');
Route::post('/admin/school-years/{schoolYear}/destroy', [SchoolYearController::class, 'update'])->name('school-years.destroy');

require __DIR__ . '/auth.php';
