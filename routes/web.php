<?php

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

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth/login');
    });
});

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/evaluation-form', [EvaluationFormController::class, 'index'])->name('evaluation.form');

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/account-archived', App\Livewire\Pages\AccountArchived\Index::class)->name('account-archived.index');
        Route::get('/account-deactivated', App\Livewire\Pages\AccountDeactivated\Index::class)->name('account-deactivated.index');
    });

Route::middleware(['auth:admin,hr,evaluator'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', App\Livewire\Pages\Admin\Dashboard\Index::class)->name('dashboard.index');
        Route::get('/subjects', App\Livewire\Pages\Admin\Subjects\Index::class)->name('subjects.index');
        Route::get('/courses', App\Livewire\Pages\Admin\Courses\Index::class)->name('courses.index');
        Route::get('/courses/{course}', App\Livewire\Pages\Admin\Courses\Course::class)->name('courses.course');
        Route::get('/departments', App\Livewire\Pages\Admin\Departments\Index::class)->name('departments.index');
        Route::get('/forms', App\Livewire\Pages\Admin\Forms\Index::class)->name('forms.index');
        Route::get('/forms/{form}', App\Livewire\Pages\Admin\Forms\Form::class)->name('forms.form');
        Route::get('/form-submission-periods', App\Livewire\Pages\Admin\FormSubmissionPeriods\Index::class)
            ->name('form-submission-periods.index');
        Route::get('/form-submissions', App\Livewire\Pages\Admin\FormSubmissions\Index::class)
            ->name('form-submissions.index');
        Route::get('/form-submissions/{formSubmission}', App\Livewire\Pages\Admin\FormSubmissions\FormSubmission::class)
            ->name('form-submissions.form-submission');
        Route::get('/school-years', App\Livewire\Pages\Admin\SchoolYears\Index::class)->name('school-years.index');
        Route::get('/sections', App\Livewire\Pages\Admin\Sections\Index::class)->name('sections.index');
        Route::get('/students', App\Livewire\Pages\Admin\Students\Index::class)->name('students.index');
        Route::get('/teachers', App\Livewire\Pages\Admin\Teachers\Index::class)->name('teachers.index');
        Route::get('/accounts', App\Livewire\Pages\Admin\Accounts\Index::class)->name('accounts.index');
    });

Route::middleware(['auth:student,teacher,dean'])
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', App\Livewire\Pages\User\Dashboard\Index::class)->name('dashboard.index');
        Route::get('/form-submission/{formSubmissionPeriod}/{evaluatee?}', App\Livewire\Pages\User\SubmitForm\Index::class)->name('form-submission.index');
        Route::middleware(['auth:teacher'])->get('/form-submissions/{formSubmission}', App\Livewire\Pages\User\FormSubmissions\FormSubmission::class)
            ->name('form-submissions.form-submission');
    });

require __DIR__ . '/auth.php';
