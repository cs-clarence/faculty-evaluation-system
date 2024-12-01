<?php

namespace App\Models;

enum FormQuestionType: string {
    case MultipleChoicesSingleSelect = 'multiple_choices_single_select';
    case MultipleChoicesMultipleSelect = 'multiple_choices_multiple_select';
    case Essay = 'essay';
}
