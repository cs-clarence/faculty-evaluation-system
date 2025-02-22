<?php
namespace App\Livewire\Pages\AccountDeactivated;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.account-deactivated.index')
            ->layout('components.layouts.user-card');
    }
}
