<?php

namespace App\Livewire\Pages\Admin\FormSubmissionPeriods;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.form-submission-periods.index')
            ->layout('components.layouts.admin');
    }
}
