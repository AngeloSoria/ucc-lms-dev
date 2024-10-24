<?php

// SIDEBAR CONTENTS
/*

    sidebar_id can be used to specify the current active page so that the sidebar-item is highlighted as the active page.    

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
            'title' => 'Dashboard',
            'icon' => 'bi-columns-gap',
            'link' => 'dashboard_admin.php',
        ],
        'programs' => [
            'title' => 'Programs',
            'icon' => 'bi-person-rolodex',
            'link' => 'program_admin.php',
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
        'users' => [
            'title' => 'Users',
            'icon' => 'bi-people',
            'link' => 'users_admin.php',
        ],
        'departments' => [
            'title' => 'Departments',
            'icon' => 'bi-building',
            'link' => 'departments_admin.php',
        ],
        'content' => [
            'title' => 'Contents',
            'icon' => 'bi-card-heading',
            'link' => 'content_admin.php',
        ],
        'events-calendar' => [
            'title' => 'Events Calendar',
            'icon' => 'bi-calendar-event',
            'link' => '#',
        ],
        'school-year' => [
            'title' => 'School Year',
            'icon' => 'bi-calendar-event',
            'link' => '#',
        ],
        'general-logs' => [
            'title' => 'General Logs',
            'icon' => 'bi-clipboard-data',
            'link' => '#',
        ],
    ],
    
    'Student' => [

    ],
];
