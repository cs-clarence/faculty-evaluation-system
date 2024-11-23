<?php

namespace App\Livewire\Pages\Admin\Subjects;

use App\Models\Subject;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $subjects = Subject::all();
        return view('livewire.pages.admin.subjects.index')
            ->with(compact('subjects'))
            ->layout('components.layouts.admin');
    }
}
