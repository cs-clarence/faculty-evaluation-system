<?php

namespace App\Livewire\Pages\Admin\Accounts;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.accounts.index')
            ->layout('components.layouts.admin');
    }
}
