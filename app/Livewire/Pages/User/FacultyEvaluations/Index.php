<?php
namespace App\Livewire\Pages\User\FacultyEvaluations;

use App\Livewire\Traits\WithSearch;
use App\Models\FormSubmission;
use App\Models\RoleCode;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;

    public function render()
    {
        $user            = Auth::user();
        $departmentId    = $user->dean->department_id;
        $formSubmissions = FormSubmission::whereHas('formSubmissionDepartment',
            fn($q) => $q->whereDepartmentId($departmentId)
        )
            ->whereHas('submissionPeriod',
                fn($q) => $q
                    ->whereHas('evaluateeRole',
                        fn($q) => $q->whereCode(RoleCode::Teacher->value)
                    )
            );

        if ($this->shouldSearch()) {
            $formSubmissions = $formSubmissions->fullTextSearch(
                [
                    'relations' => [
                        'submissionPeriod' => [
                            'columns'   => ['name'],
                            'relations' => [
                                'evaluateeRole' => [
                                    'columns' => ['display_name', 'code'],
                                ],
                            ],
                        ],
                        'evaluatee'        => [
                            'columns' => ['name', 'email'],
                        ],
                    ],
                ],
                $this->searchText
            );
        }

        $formSubmissions = $formSubmissions->cursorPaginate(15);

        return view('livewire.pages.user.faculty-evaluations.index', [
            'formSubmissions' => $formSubmissions,
        ])
            ->layout('components.layouts.user');
    }
}
