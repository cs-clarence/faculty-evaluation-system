<?php

namespace Database\Initializers\Base;

use App;

abstract class Initializer
{
    abstract public function run();

    public function call(array $classes)
    {
        foreach ($classes as $class) {
            $init = App::make($class);
            $init->run();
        }
    }
}
