<?php
namespace Database\Initializers;

use App\Models\Department;
use Database\Initializers\Base\Initializer;

class DepartmentInitializer extends Initializer
{
    /**
     * Run the database seeds.
     */
    private $defaults = [
        [
            'code' => 'CASSED',
            'name' => 'College Of Arts And Social Sciences And Education',
        ],
        [
            'code' => 'COB',
            'name' => 'College Of Business',
        ],
        [
            'code' => 'CCIS',
            'name' => 'College Of Computing And Information Sciences',
        ],
        [
            'code' => 'COC',
            'name' => 'College Of Criminology',
        ],
        [
            'code' => 'COE',
            'name' => 'College Of Engineering',
        ],
        [
            'code' => 'CHTM',
            'name' => 'College Of Hospitality Management',
        ],
        [
            'code' => 'CON',
            'name' => 'College Of Nursing',
        ],
    ];

    public function run(): void
    {
        if (env('APP_ENV') === 'production') {
            return;
        }
        foreach ($this->defaults as $department) {
            if (! Department::where('code', $department['code'])->exists()) {
                Department::create($department);
            }
        }
    }
}
