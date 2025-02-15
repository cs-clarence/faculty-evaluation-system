<?php
namespace App\Livewire\Pages\Dean\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.dean.dashboard.index')
            ->layout('components.layouts.user');
    }
}
