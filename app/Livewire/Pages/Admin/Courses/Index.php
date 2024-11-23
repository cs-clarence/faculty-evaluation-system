<?php

namespace App\Livewire\Pages\Admin\Courses;

use App\Models\Course;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $courses = Course::all();
        return view('livewire.pages.admin.courses.index')
            ->with(compact('courses'))
            ->layout('components.layouts.admin');
    }
}
