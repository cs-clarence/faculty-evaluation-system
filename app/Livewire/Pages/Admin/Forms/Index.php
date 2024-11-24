<?php

namespace App\Livewire\Pages\Admin\Forms;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.forms.index')
            ->layout('components.layouts.admin');
    }
}
