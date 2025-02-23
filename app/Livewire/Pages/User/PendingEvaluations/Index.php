<?php
namespace App\Livewire\Pages\User\PendingEvaluations;

use App\Facades\Services\PendingEvaluationsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user               = Auth::user();
        $pendingEvaluations = PendingEvaluationsService::getPendingEvaluations($user);

        return view('livewire.pages.user.pending-evaluations.index', [
            'pendingEvaluations' => $pendingEvaluations,
        ])
            ->layout('components.layouts.user');
    }
}
