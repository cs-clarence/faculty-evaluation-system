<?php

namespace App\Livewire\Pages\Student\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.student.dashboard.index')
            ->layout('components.layouts.student');
    }
}
