<?php

namespace App\Livewire\Pages\Admin\Students;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.students.index')
            ->layout('components.layouts.admin');
    }
}
