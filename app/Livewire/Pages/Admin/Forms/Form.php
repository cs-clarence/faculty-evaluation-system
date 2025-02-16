<?php
namespace App\Livewire\Pages\Admin\Forms;

use App\Models\Form as Model;
use Livewire\Component;

class Form extends Component
{

    public Model $form;

    public function render()
    {
        return view('livewire.pages.admin.forms.form')
            ->layout('components.layouts.admin');
    }
}
