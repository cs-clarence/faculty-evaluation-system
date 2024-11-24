<?php

namespace App\Livewire\Pages\Admin\Sections;

use App\Livewire\Forms\SectionForm;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Support\Str;
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

    public function updated(string $name, $value)
    {
        if (($name === 'form.year_level' || $name === 'form.semester' || $name === 'form.name' || $name === 'form.course_id') && (
            isset($this->form->name) && $this->form->name !== '' && isset($this->form->year_level) && isset($this->form->semester) && isset($this->form->course_id)
        ) && (!isset($this->form->code) || $this->form->code === '')) {
            $course = Course::whereId($this->form->course_id)->first();
            $name = preg_replace('/\s+/', '_', Str::upper($this->form->name));
            $paddedYearLevel = Str::padLeft($this->form->year_level, 2, '0');
            $paddedSemester = Str::padLeft($this->form->semester, 2, '0');
            $this->form->code = "{$course->code}_Y{$paddedYearLevel}_S{$paddedSemester}_{$name}";
        }
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
