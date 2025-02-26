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
        Route::get('/account-archived', App\Livewire\Pages\AccountArchived\Index::class)
            ->name('account-archived.index');
        Route::get('/account-deactivated', App\Livewire\Pages\AccountDeactivated\Index::class)
            ->name('account-deactivated.index');
        Route::get('/account-settings', App\Livewire\Pages\AllRoles\AccountSettings::class)
            ->name('account-settings');
        Route::get('/submit-evaluation/{formSubmissionPeriod}', App\Livewire\Pages\AllRoles\SubmitEvaluation::class)
            ->name('submit-evaluation');
        Route::get('/view-evaluation/{formSubmission}', App\Livewire\Pages\AllRoles\ViewEvaluation::class)
            ->name('view-evaluation');
    });

Route::middleware(['auth:admin,human_resources_staff,evaluator'])
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
        Route::get('/pending-evaluations', App\Livewire\Pages\Admin\PendingEvaluations\Index::class)
            ->name('pending-evaluations.index');
        Route::get('/submitted-evaluations', App\Livewire\Pages\Admin\SubmittedEvaluations\Index::class)
            ->name('submitted-evaluations.index');
        Route::get('/school-years', App\Livewire\Pages\Admin\SchoolYears\Index::class)->name('school-years.index');
        Route::get('/sections', App\Livewire\Pages\Admin\Sections\Index::class)->name('sections.index');
        Route::get('/students', App\Livewire\Pages\Admin\Students\Index::class)->name('students.index');
        Route::get('/teachers', App\Livewire\Pages\Admin\Teachers\Index::class)->name('teachers.index');
        Route::get('/deans', App\Livewire\Pages\Admin\Deans\Index::class)->name('deans.index');
        Route::get('/accounts', App\Livewire\Pages\Admin\Accounts\Index::class)->name('accounts.index');
    });

Route::middleware(['auth:student,teacher,dean'])
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', App\Livewire\Pages\User\Dashboard\Index::class)->name('dashboard.index');
        Route::get('/pending-evalutations', App\Livewire\Pages\User\PendingEvaluations\Index::class)->name('pending-evaluations.index');
        Route::get('/faculity-evalutations', App\Livewire\Pages\User\FacultyEvaluations\Index::class)->name('faculty-evaluations.index');
        Route::get('/submitted-evalutations', App\Livewire\Pages\User\SubmittedEvaluations\Index::class)->name('submitted-evaluations.index');
        Route::get('/received-evaluations', App\Livewire\Pages\User\ReceivedEvaluations\Index::class)->name('received-evaluations.index');
    });

require __DIR__ . '/auth.php';
