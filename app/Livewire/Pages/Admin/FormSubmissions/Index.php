<?php
namespace App\Livewire\Pages\Admin\FormSubmissions;

use App\Livewire\Traits\WithSearch;
use App\Models\FormSubmission;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithoutUrlPagination, WithPagination, WithSearch;

    public function render()
    {
        $formSubmissions = FormSubmission::with([
            'evaluator',
            'evaluatee',
            'submissionPeriod.form.questions',
        ]);

        if ($this->shouldSearch()) {
            $formSubmissions = $formSubmissions->fullTextSearch([
                'relations' => [
                    'evaluator'        => ['name', 'email'],
                    'evaluatee'        => ['name', 'email'],
                    'submissionPeriod' => [
                        'relations' => [
                            'evaluateeRole' => ['display_name', 'code'],
                            'evaluatorRole' => ['display_name', 'code'],
                        ],
                    ],
                ],
            ], $this->searchText);
        }

        $formSubmissions = $formSubmissions->cursorPaginate();

        return view('livewire.pages.admin.form-submissions.index')
            ->with(compact('formSubmissions'))
            ->layout('components.layouts.admin');
    }
}
