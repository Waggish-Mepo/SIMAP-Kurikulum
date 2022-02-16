<?php

use App\Models\Teacher;
use App\Models\User;

return [
    'user' => [
        'roles' => [
            User::ADMIN,
        ],
    ],
    'teacher' => [
        'gender' => [
            Teacher::LAKILAKI,
            Teacher::PEREMPUAN,
        ],
    ],
];
