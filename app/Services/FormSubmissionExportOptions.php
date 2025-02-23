<?php
namespace App\Services;

class FormSubmissionExportOptions
{
    public function __construct(
        public bool $showText = true,
        public bool $showReason = true,
        public bool $showInterpretation = true,
        public bool $showEvaluator = true,
    ) {}
}
