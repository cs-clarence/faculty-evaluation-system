<?php

namespace App\Livewire\Pages\Admin\Forms;

use App\Livewire\Forms\FormForm;
use App\Models\Form;
use Livewire\Component;

class Index extends Component
{
    public bool $isFormOpen = false;
    public FormForm $form;
    public ?Form $model;

    public function render()
    {
        $forms = Form::withCount(['sections', 'questions'])
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at')
            ->lazy();

        return view('livewire.pages.admin.forms.index')
            ->with(compact('forms'))
            ->layout('components.layouts.admin');
    }

    public function save()
    {
        $this->form->save();
        $this->closeForm();
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

    public function delete(Form $model)
    {
        $model->delete();
    }

    public function edit(Form $model)
    {
        $this->model = $model;
        $this->isFormOpen = true;
        $this->form->set($model);
    }

    public function archive(Form $model)
    {
        $model->archive();
    }

    public function unarchive(Form $model)
    {
        $model->unarchive();
    }

    public function open(Form $model)
    {
        $model->open();
    }

    public function close(Form $model)
    {
        $model->close();
    }
}
