<?php

namespace App\Livewire\Forms;

use Livewire\Form;

abstract class BaseForm extends Form
{
    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    abstract public function submit();
}
