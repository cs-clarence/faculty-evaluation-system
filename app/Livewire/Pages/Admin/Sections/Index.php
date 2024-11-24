<?php

namespace App\Livewire\Pages\Admin\Sections;

use App\Livewire\Forms\SectionForm;
use App\Models\Course;
use App\Models\Section;
use Livewire\Component;

class Index extends Component
{
    public ?Section $model;
    public bool $isFormOpen = false;
    public SectionForm $form;

    public function render()
    {
        $sections = Section::orderBy('year_level')
            ->orderBy('code')
            ->lazy();
        $courses = Course::withoutArchived()
            ->orderBy('department_id')
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at')
            ->lazy();

        return view('livewire.pages.admin.sections.index')
            ->with(compact('sections', 'courses'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->form->clear();
        $this->model = null;
    }

    public function edit(Section $section)
    {
        $this->model = $section;
        $this->isFormOpen = true;
        $this->form->set($section);
    }

    public function save()
    {
        $this->form->save();
        $this->closeForm();
    }

    public function archive(Section $section)
    {
        $section->archive();
    }

    public function delete(Section $section)
    {
        $section->delete();
    }

    public function unarchive(Section $section)
    {
        $section->unarchive();
    }
}
