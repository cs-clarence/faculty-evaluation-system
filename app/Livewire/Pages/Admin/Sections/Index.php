<?php

namespace App\Livewire\Pages\Admin\Sections;

use App\Facades\Helpers\SectionHelper;
use App\Livewire\Forms\SectionForm;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;

class Index extends Component
{
    public ?Section $model;
    public bool $isFormOpen = false;
    public SectionForm $form;

    public function render()
    {
        $sections = Section::with(['course' => function (BelongsTo $builder) {
            $builder->select(['id', 'name']);
        }])
            ->orderBy('course_id')
            ->orderBy('year_level')
            ->orderBy('semester')
            ->orderBy('name')
            ->orderBy('code')
            ->get();

        $courses = Course::query()
            ->withoutArchived()
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
            $this->form->code = SectionHelper::generateCode($this->form->course_id, $this->form->year_level, $this->form->semester, $this->form->name);
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
        $this->form->submit();
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
