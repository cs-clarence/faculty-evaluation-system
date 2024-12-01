<?php

return [
    'driver' => env('CHAT_COMPLETION_DRIVER', 'groq'),

    'completion_drivers' => [
        'groq' => [
            'api_key' => env('GROQ_API_KEY'),
            'model' => env('GROQ_MODEL'),
        ],
    ],
];