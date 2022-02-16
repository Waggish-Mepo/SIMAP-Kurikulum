<?php

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;

return [
    'user' => [
        'roles' => [
            User::ADMIN,
        ],
    ],
    'subject' => [
        'group' => [
            Subject::MUATAN_A,
            Subject::MUATAN_B,
            Subject::MUATAN_C,
        ]
    ],
    'teacher' => [
        'gender' => [
            Teacher::LAKILAKI,
            Teacher::PEREMPUAN,
        ],
    ],
];
