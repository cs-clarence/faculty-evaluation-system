<?php

namespace App\Livewire\Pages\AccountArchived;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.account-archived.index')
            ->layout('components.layouts.guest');
    }
}
