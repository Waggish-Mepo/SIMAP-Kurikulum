<?php

use App\Models\Student;
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
        'entry_years' => [
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
        'curriculums' => [
            'K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018',
            'K13 Revisi 2017 SMK | Perdirjen Dikdasmen No. 464/D.D5/KR/2018',
            'K13 Pandemi 2020 | SK Balitbang Kemendikbud No. 018/H/KR/2020',
            'K21 | Sekolah Penggerak',
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
    'student' => [
        'gender' => [
            Student::LAKILAKI,
            Student::PEREMPUAN,
        ],
    ],
    'majors' => [
        'abbreviations' => [
            'K13' => [
                'RPL',
                'TKJ',
                'MMD',
                'BDP',
                'OTKP',
                'HTL',
                'TBG',
            ],
            'K21' => [
                'PPLG',
                'TJKT',
                'DKV',
                'MPLB',
                'KLNR',
                'PMN',
                'HTL',
            ],
        ],
        'wikrama_majors' => [
            'K13' => [
                [
                    'expertise' => 'Teknologi Informasi dan Komunikasi',
                    'program' => 'Teknik Komputer dan Informatika',
                    'name' => 'Rekayasa Perangkat Lunak',
                    'abbreviation' => 'RPL',
                ],
                [
                    'expertise' => 'Teknologi Informasi dan Komunikasi',
                    'program' => 'Teknik Komputer dan Informatika',
                    'name' => 'Teknik Komputer dan Jaringan',
                    'abbreviation' => 'TKJ',
                ],
                [
                    'expertise' => 'Teknologi Informasi dan Komunikasi',
                    'program' => 'Teknik Komputer dan Informatika',
                    'name' => 'Multimedia',
                    'abbreviation' => 'MMD',
                ],
                [
                    'expertise' => 'Bisnis dan Manajemen',
                    'program' => 'Bisnis dan Pemasaran',
                    'name' => 'Bisnis Daring dan Pemasaran',
                    'abbreviation' => 'BDP',
                ],
                [
                    'expertise' => 'Bisnis dan Manajemen',
                    'program' => 'Manajemen Perkantoran',
                    'name' => 'Otomatisasi dan Tata Kelola Perkantoran',
                    'abbreviation' => 'OTKP',
                ],
                [
                    'expertise' => 'Pariwisata',
                    'program' => 'Perhotelan dan Jasa Pariwisata',
                    'name' => 'Perhotelan',
                    'abbreviation' => 'HTL',
                ],
                [
                    'expertise' => 'Pariwisata',
                    'program' => 'Kuliner',
                    'name' => 'Tata Boga',
                    'abbreviation' => 'TBG',
                ],
            ],
            'K21' => [
                [
                    'expertise' => 'Teknologi Informasi',
                    'program' => '',
                    'name' => 'Pengembangan Perangkat Lunak dan Gim',
                    'abbreviation' => 'PPLG'
                ],
                [
                    'expertise' => 'Teknologi Informasi',
                    'program' => '',
                    'name' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                    'abbreviation' => 'TJKT'
                ],
                [
                    'expertise' => 'Seni dan Ekonomi Kreatif',
                    'program' => '',
                    'name' => 'Desain Komunikasi Visual',
                    'abbreviation' => 'DKV'
                ],
                [
                    'expertise' => 'Bisnis dan Manajemen',
                    'program' => '',
                    'name' => 'Manajemen Perkantoran dan Layanan Bisnis',
                    'abbreviation' => 'MPLB'
                ],
                [
                    'expertise' => 'Bisnis dan Manajemen',
                    'program' => '',
                    'name' => 'Pemasaran',
                    'abbreviation' => 'PMN'
                ],
                [
                    'expertise' => 'Pariwisata',
                    'program' => '',
                    'name' => 'Perhotelan',
                    'abbreviation' => 'HTL'
                ],
                [
                    'expertise' => 'Pariwisata',
                    'program' => '',
                    'name' => 'Kuliner',
                    'abbreviation' => 'KLNR'
                ],
            ],
        ]
    ]
];
