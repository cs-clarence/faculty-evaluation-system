<?php
namespace Database\Initializers;

use App\Models\Form;
use App\Models\FormQuestionType;
use Database\Initializers\Base\Initializer;

class FormInitializer extends Initializer
{
    private const DEFAULT_DESCRIPTION      = '1 - Strongly Disagree, 2 - Disagree, 3 - Neutral, 4 - Agree, 5 - Strongly Agree';
    private const DEFAULT_MULTIPLE_CHOICES = [
        [
            'label'          => 'Strongly Disagree',
            'value'          => 1,
            'interpretation' => 'Strongly Disagree',
        ],
        [
            'label'          => 'Disagree',
            'value'          => 2,
            'interpretation' => 'Disagree',
        ],
        [
            'label'          => 'Neutral',
            'value'          => 3,
            'interpretation' => 'Neutral',
        ],
        [
            'label'          => 'Agree',
            'value'          => 4,
            'interpretation' => 'Agree',
        ],
        [
            'label'          => 'Strongly Agree',
            'value'          => 5,
            'interpretation' => 'Strongly Agree',
        ],
    ];

    private const DEFAULT = [
        'name'        => 'Default Form',
        'description' => 'This is a default form',
        'sections'    => [
            [
                'title'       => 'Teacher\'s Personality, Promptness, and Preparedness',
                'description' => self::DEFAULT_DESCRIPTION,
                'questions'   => [
                    [
                        'title'   => 'The teacher/instructor is punctual on starting online meetings and in postings of assignments or work tasks.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor demonstrated enthusiasm and knowledge during discussions of topics in synchronous meetings.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor is effective and creative in giving examples and sharing presentations during online discussions.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor provides encouragement to students to participate and accomplish tasks on time.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'Dresses Neatly and Appropriately.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                ],
            ],
            [
                'title'       => 'Communication and Interaction',
                'description' => self::DEFAULT_DESCRIPTION,
                'questions'   => [
                    [
                        'title'   => 'The teacher/instructor promotes mutual respect among students.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor responds constructively to student questions, opinions and other inputs.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor encourages interaction among all the members of the class.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor promptly and effectively handles inappropriate discussion postings or other unacceptable online behaviors.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                ],
            ],
            [
                'title'       => 'Course Design and Content',
                'description' => self::DEFAULT_DESCRIPTION,
                'questions'   => [
                    [
                        'title'   => 'The teacher/instructor demonstrates appropriate expertise and knowledge of the course subject.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor provides content that is appropriate and relevant to the subject.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor explains difficult terms, concepts or problems about the topic in various ways and in levels that every student can understand.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor relates coursework to course content.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The online subject/course clearly articulates course policies and content.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The course subject utilizes a variety of online resources and tools to facilitate student comprehension and engagement in learning the course.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor provides an online syllabus that details the terms of class interaction for both teacher and students, with clear expectations and grading criteria.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                ],
            ],
            [
                'title'       => 'Classroom Management Skills',
                'description' => self::DEFAULT_DESCRIPTION,
                'questions'   => [
                    [
                        'title'   => 'The teacher/instructor demonstrates the skills and ability to effectively use the prescribed platforms and applications for online learning.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor incorporates relevant multimedia and other visual resources into the online module.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor utilizes synchronous and asynchronous tools (e.g., discussion boards, chat tools, file and link sharing) effectively.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                    [
                        'title'   => 'The teacher/instructor provides student-centered lessons and activities that are based on concepts of active learning and that are connected to real-world applications.',
                        'type'    => FormQuestionType::MultipleChoicesSingleSelect->value,
                        'options' => self::DEFAULT_MULTIPLE_CHOICES,
                    ],
                ],
            ],
            [
                'title'       => 'Answer the following questions. Be reasonable in giving comments.',
                'description' => null,
                'questions'   => [
                    [
                        'title'                    => 'What are the characterisitics that you like to your teacher?',
                        'type'                     => FormQuestionType::Essay->value,
                        'essay_type_configuration' => [
                            'value_scale_from' => 1.0,
                            'value_scale_to'   => 5.0,
                        ],
                    ],
                    [
                        'title'                    => 'What are the different traits and qualities you like to improve by your teacher?',
                        'type'                     => FormQuestionType::Essay->value,
                        'essay_type_configuration' => [
                            'value_scale_from' => 1.0,
                            'value_scale_to'   => 5.0,
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function run(): void
    {
        $f = self::DEFAULT;
        if (! Form::whereName($f['name'])->exists()) {
            /**
             * @var Form
             */
            $form = Form::create([
                'name'        => self::DEFAULT['name'],
                'description' => $f['description'],
            ]);

            $form->save();

            foreach ($f['sections'] as $section) {
                /**
                 * @var \App\Models\FormSection
                 */
                $s = $form->sections()->create([
                    'form_id'         => $form->id,
                    'title'           => $section['title'],
                    'description'     => $section['description'],
                    'order_numerator' => 0,
                ]);

                foreach ($section['questions'] as $question) {
                    /**
                     * @var \App\Models\FormQuestion
                     */
                    $q = $s->questions()->create([
                        'title'           => $question['title'],
                        'type'            => $question['type'],
                        'form_section_id' => $s->id,
                        'form_id'         => $form->id,
                        'order_numerator' => 0,
                    ]);

                    foreach (($question['options'] ?? []) as $option) {
                        $q->options()->create([
                            'label'            => $option['label'],
                            'value'            => $option['value'],
                            'interpretation'   => $option['interpretation'] ?? null,
                            'form_question_id' => $q->id,
                            'order_numerator'  => 0,
                        ]);
                    }

                    $essayTypeConfiguration = $question['essay_type_configuration'] ?? null;
                    if (isset($essayTypeConfiguration)) {
                        $q->essayTypeConfiguration()->create(
                            $essayTypeConfiguration
                        );
                    }
                }
            }
        }
    }
}
