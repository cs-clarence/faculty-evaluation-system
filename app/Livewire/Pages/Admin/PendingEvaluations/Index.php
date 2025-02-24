<?php
namespace App\Livewire\Pages\Admin\PendingEvaluations;

use App\Facades\Services\PendingEvaluationsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $user               = Auth::user();
        $pendingEvaluations = PendingEvaluationsService::getPendingEvaluations($user);
        return view('livewire.pages.admin.pending-evaluations.index', [
            'pendingEvaluations' => $pendingEvaluations,
        ])
            ->layout('components.layouts.admin');
    }
}
