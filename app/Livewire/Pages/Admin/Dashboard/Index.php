<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.dashboard.index')
            ->layout('components.layouts.admin');
    }
}
