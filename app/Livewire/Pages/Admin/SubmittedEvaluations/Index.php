<?php
namespace App\Livewire\Pages\Admin\SubmittedEvaluations;

use App\Livewire\Traits\WithSearch;
use App\Models\FormSubmission;
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
        $formSubmissions = FormSubmission::whereEvaluatorId($user->id);

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

        return view('livewire.pages.admin.submitted-evaluations.index', [
            'formSubmissions' => $formSubmissions,
        ])
            ->layout('components.layouts.admin');
    }
}
