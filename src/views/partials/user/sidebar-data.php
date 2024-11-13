<?php

// SIDEBAR CONTENTS
/*

    '<role>' => [
        '<sidebar_id>' => [
            'title' => '<visual_text>',
            'icon' => '<bootstrap_icon_class>',
            'link' => '<src_path>',
        ],
    ]
*/
$sidebar_content = [
    'Admin' => [
        'dashboard' => [
            'title' => 'Overview',
            'icon' => 'bi-columns-gap',
            'link' => 'dashboard_admin.php',
        ],
        'content_management' => [
            'title' => 'Academic',
            'icon' => 'bi-card-list',
            'isGroup' => true,
            'sublinks' => [
                'programs' => [
                    'title' => 'Programs',
                    'icon' => 'bi-person-rolodex',
                    'link' => 'programs_admin.php',
                ],
                'sections' => [
                    'title' => 'Sections',
                    'icon' => 'bi-file-earmark-text',
                    'link' => 'section_admin.php',
                ],
                'subjects' => [
                    'title' => 'Subjects',
                    'icon' => 'bi-file-earmark-text',
                    'link' => 'subjects_admin.php',
                ],
            ],
        ],
        'users_roles_management' => [
            'title' => 'People',
            'icon' => 'bi-people',
            'isGroup' => true,
            'sublinks' => [
                'users' => [
                    'title' => 'Users',
                    'icon' => 'bi-person-check',
                    'link' => 'users_admin.php',
                ],
                'departments' => [
                    'title' => 'Departments',
                    'icon' => 'bi-building',
                    'link' => 'departments_admin.php',
                ],
            ],
        ],
        'settings' => [
            'title' => 'Settings',
            'icon' => 'bi-gear-fill',
            'isGroup' => true,
            'sublinks' => [
                'academic-calendar' => [
                    'title' => 'Academic Calendar',
                    'icon' => 'bi-calendar-event',
                    'link' => 'academic_calendar_admin.php',
                ],
                'content' => [
                    'title' => 'Carousel',
                    'icon' => 'bi-images',
                    'link' => 'content_admin.php',
                ],
                'general-logs' => [
                    'title' => 'General Logs',
                    'icon' => 'bi-clipboard-data',
                    'link' => 'general_logs_admin.php',
                ],
            ],
        ],
    ],

    'Level Coordinator' => [
        'sections' => [
            'title' => 'Sections',
            'icon' => 'bi-file-earmark-text',
            'link' => 'section_admin.php',
        ],
        'subjects' => [
            'title' => 'Subjects',
            'icon' => 'bi-file-earmark-text',
            'link' => 'subjects_admin.php',
        ],
    ],
];
