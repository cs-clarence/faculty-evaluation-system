<?php

namespace App\Livewire\Pages\Admin\Accounts;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;

    public function render()
    {
        $users = User::with(['role'])->orderBy('name')->orderBy('email')->lazy();

        return view('livewire.pages.admin.accounts.index')
            ->with(compact('users'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
    }

    public function edit(User $model)
    {
        $this->model = $model;
        $this->openForm();
    }

    public function delete(User $model)
    {
        $model->delete();
    }

    public function archive(User $model)
    {
        $model->archive();
    }

    public function unarchive(User $model)
    {
        $model->unarchive();
    }
}
