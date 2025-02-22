<?php
namespace App\Livewire\Traits;

trait WithSearch
{
    public ?string $searchText = null;

    public function search(string $text)
    {
        $this->searchText = $text;
    }

    public function shouldSearch()
    {
        return isset($this->searchText) && trim($this->searchText) !== '';
    }
}
