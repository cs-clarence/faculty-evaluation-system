<?php

namespace App\Livewire\Pages\Admin\Sections;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.sections.index')
            ->layout('components.layouts.admin');
    }
}
