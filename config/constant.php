<?php

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;

return [
    'common' => [
        'school_years' => [
            '2010/2011',
            '2011/2012',
            '2012/2013',
            '2013/2014',
            '2014/2015',
            '2015/2016',
            '2016/2017',
            '2017/2018',
            '2018/2019',
            '2019/2020',
            '2020/2021',
            '2021/2022',
            '2022/2023',
            '2023/2024',
            '2024/2025',
            '2025/2026',
            '2026/2027',
            '2027/2028',
            '2028/2029',
            '2029/2030',
        ],
    ],
    'user' => [
        'roles' => [
            User::ADMIN,
            User::TEACHER,
            User::STUDENT,
        ],
    ],
    'subject' => [
        'group' => [
            Subject::MUATAN_A,
            Subject::MUATAN_B,
            Subject::MUATAN_C,
            Subject::MUATAN_C1,
            Subject::MUATAN_C2,
            Subject::MUATAN_C3,
        ]
    ],
    'teacher' => [
        'gender' => [
            Teacher::LAKILAKI,
            Teacher::PEREMPUAN,
        ],
    ],
];
