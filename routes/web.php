<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EvaluationFormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/home', [HomeController::class,'index'])->middleware('auth')->name('home');

//Route Subject
Route::get('/subject', [SubjectController::class, 'index'])->name('subject');
Route::post('/subjects/store', [SubjectController::class, 'store'])->name('subjects.store');

//Route Course
Route::get('/course', [CourseController::class, 'index'])->name('course');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

//Route Course Semester

// Route to store a new semester for a specific course
Route::post('/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');

// Route to show the semesters for a specific course
Route::get('/courses/{course}/semesters', [CourseController::class, 'showSemesters'])->name('courses.showSemesters');
// Route to store a new semester for a specific course
Route::post('/courses/{course}/semesters', [CourseController::class, 'storeSemester'])->name('courses.storeSemester');

//Route Evaluation Form
Route::get('/evaluation-form',[EvaluationFormController::class, 'index'])->name('evaluation.form');

//Route for department
Route::get('/department', [CourseController::class, 'department_index'])->name('department');
Route::post('/departments', [CourseController::class, 'department_store'])->name('department.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
