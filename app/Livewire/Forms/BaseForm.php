<?php
namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * @template T
 */
abstract class BaseForm extends Form
{
    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    abstract public function submit();

    /**
     * @param T $model
     */
    abstract public function set(mixed $model);
}
