<?php

namespace App\Livewire\Pages\Admin\Teachers;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.teachers.index')
            ->layout('components.layouts.admin');
    }
}
