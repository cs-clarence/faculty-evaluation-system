<?php
namespace App\Models\Import;

class StudentImport
{
    public function __construct(
        public string $studentNumber,
        public string $name,
        public string $email,
        public string $courseCode,
        public int $startingSchoolYearFrom,
        public int $startingSchoolYearTo,
    ) {}
}
