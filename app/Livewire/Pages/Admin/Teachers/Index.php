<?php

namespace App\Livewire\Pages\Admin\Teachers;

use App\Models\Teacher;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $teachers = Teacher::withCount(['teacherSubjects', 'teacherSemesters'])
            ->lazy();

        return view('livewire.pages.admin.teachers.index')
            ->with(compact('teachers'))
            ->layout('components.layouts.admin');
    }
}
