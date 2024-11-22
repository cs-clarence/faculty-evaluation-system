<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $defaultDepartments = [
        [
            'code' => 'CASSE',
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
            'code' => 'COED',
            'name' => 'College Of Education',
        ],
        [
            'code' => 'COE',
            'name' => 'College Of Engineering',
        ],
        [
            'code' => 'COHM',
            'name' => 'College Of Hospitality Management',
        ],
        [
            'code' => 'CON',
            'name' => 'College Of Nursing',
        ],
    ];

    public function run(): void
    {
        foreach ($this->defaultDepartments as $department) {
            if (!Department::where('code', $department['code'])->exists()) {
                Department::create($department);
            }
        }
    }
}
