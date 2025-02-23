<?php
namespace App\Livewire\Pages\User\ReceivedEvaluations;

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
        $formSubmissions = FormSubmission::whereEvaluateeId(Auth::user()->id);

        if ($this->shouldSearch()) {
            $formSubmissions = $formSubmissions->fullTextSearch(
                [
                    'relations' => [
                        'submissionPeriod' => [
                            'columns'   => ['name'],
                            'relations' => [
                                'evaluatorRole' => [
                                    'columns' => ['display_name', 'code'],
                                ],
                            ],
                        ],
                    ],
                ],
                $this->searchText
            );
        }

        $formSubmissions = $formSubmissions->cursorPaginate(15);

        return view('livewire.pages.user.received-evaluations.index', [
            'formSubmissions' => $formSubmissions,
        ])
            ->layout('components.layouts.user');
    }
}
