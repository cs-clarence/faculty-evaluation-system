<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $data,
        public $columns,
        public $key = 'id',
        public $empty = 'No data found'
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.table', [
            'data'    => $this->data,
            'columns' => $this->columns,
            'key'     => $this->key,
        ]);
    }

    public function walkAndGetValue(string $property, $data)
    {
        $path    = preg_split('/\./', $property);
        $current = $data;

        foreach ($path as $p) {
            if (is_array($current)) {
                $current = $current[$p];
            } else {
                $current = $current->$p;
            }
        }

        return $current;
    }
    public function getValue(callable | string $property, $data)
    {
        if (is_callable($property)) {
            return $property($data);
        } elseif (is_array($data)) {
            return $this->walkAndGetValue($property, $data);
        } else {
            return $this->walkAndGetValue($property, $data);
        }
    }
}
