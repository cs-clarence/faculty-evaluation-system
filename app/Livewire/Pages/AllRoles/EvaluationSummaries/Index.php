<?php
namespace App\Livewire\Pages\AllRoles\EvaluationSummaries;

use App\Facades\Services\EvaluationSummaryService;
use App\Livewire\Traits\WithSearch;
use App\Models\CourseSubject;
use App\Models\Semester;
use App\Models\Summaries\DetailedEvaluationSummary;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    use WithSearch;
    private ?DetailedEvaluationSummary $detailedEvaluationSummary = null;

    public ?int $filter_by_evaluatee_id      = null;
    public ?int $filter_by_semester_id       = null;
    public ?int $filter_by_course_subject_id = null;

    public function render()
    {
        $user = Auth::user();

        $view = view('livewire.pages.all-roles.evaluation-summaries.index', [
            'data'                      => EvaluationSummaryService::getSummaries($user->isTeacher() ? $user : null, function ($q) {
                if (isset($this->filter_by_evaluatee_id)) {
                    $q = $q->whereEvaluateeId($this->filter_by_evaluatee_id);
                }

                if (isset($this->filter_by_semester_id)) {
                    $q = $q->whereHas('submissionPeriod', fn($q) => $q->whereHas('formSubmissionPeriodSemester', fn($q) => $q->whereSemesterId($this->filter_by_semester_id)));
                }

                if (isset($this->filter_by_course_subject_id)) {
                    $q = $q->whereHas('formSubmissionSubject', fn($q) => $q->whereCourseSubjectId($this->filter_by_course_subject_id));
                }

                return $q;
            }),
            'detailedEvaluationSummary' => $this->detailedEvaluationSummary,
            'semesters'                 => Semester::lazy(),
            'courseSubjects'            => CourseSubject::lazy(),
            'evaluatees'                => User::has('evaluationsReceived')->lazy(),
        ]);
        if ($user->isAdmin()
            || $user->isHumanResourcesStaff()
            || $user->isRegistrar()) {
            return $view->layout('components.layouts.admin');
        } else {
            return $view->layout('components.layouts.user');
        }
    }

    public function viewDetailedEvaluationSummary(User $user, Semester $semester, ?CourseSubject $courseSubject)
    {
        $this->detailedEvaluationSummary = EvaluationSummaryService::getDetailedEvaluationSummary($user, $semester, $courseSubject);
    }

    public function closeDetailedEvaluationSummary()
    {
        $this->detailedEvaluationSummary = null;
    }

    public function resetFilters()
    {
        $this->filter_by_evaluatee_id      = null;
        $this->filter_by_course_subject_id = null;
        $this->filter_by_semester_id       = null;
    }
}
