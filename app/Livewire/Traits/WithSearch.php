<?php
namespace App\Livewire\Traits;

use Livewire\WithPagination;

trait WithSearch
{
    public ?string $searchText = null;

    public function search(string $text)
    {
        if (in_array(WithPagination::class, class_uses_recursive(get_class($this)))) {
            $this->resetPage($this->pageName ?? 'cursor');
        }

        $this->searchText = $text;

    }

    public function shouldSearch()
    {
        return isset($this->searchText) && trim($this->searchText) !== '';
    }
}
