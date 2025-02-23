<?php
namespace App\Livewire\Pages\User\Dashboard;

use App\Facades\Services\PendingEvaluationsService;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $statistics = [];
        $user       = Auth::user();

        if ($user->role->can_be_evaluatee) {
            $statistics[] = [
                'label' => 'Evaluations Received',
                'value' => FormSubmission::whereEvaluateeId($user->id)->count(),
                'href'  => route('user.received-evaluations.index'),
            ];
        }

        if ($user->role->can_be_evaluator) {
            $count        = count(PendingEvaluationsService::getPendingEvaluations($user));
            $statistics[] = [
                'label' => 'Pending Evaluations',
                'value' => $count,
                'href'  => route('user.pending-evaluations.index'),
            ];

            $statistics[] = [
                'label' => 'Evaluations Submitted',
                'value' => FormSubmission::whereEvaluatorId($user->id)->count(),
                'href'  => route('user.submitted-evaluations.index'),
            ];
        }

        return view('livewire.pages.user.dashboard.index', [
            'statistics' => $statistics,
        ])
            ->layout('components.layouts.user');
    }
}
