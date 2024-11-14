<?php


define('BASE_PATH', __DIR__ . '../../../');
define('BASE_PATH_LINK', '/ucc-lms-dev/');
define('BASE_URL', $_SERVER['DOCUMENT_ROOT'] . BASE_PATH_LINK);


// Load environment variables from .env file
define('VENDOR_AUTO_LOAD', BASE_PATH . 'vendor/autoload.php');


define('UPLOAD_PATH', [
    'System' => BASE_PATH_LINK . 'src/uploads/system',
    'User' => BASE_PATH_LINK . 'src/uploads/user',
]);

define('FILE_PATHS', [
    'DATABASE' => __DIR__ . '/connection.php',

    'LOGS' => BASE_PATH . 'logs.txt',

    'Functions' => [
        'ToastLogger' => __DIR__ . '../../functions/ToastLogger.php',
        'PHPLogger' => __DIR__ . '../../functions/PHPLogger.php',
        'SessionChecker' => __DIR__ . '../../functions/sessionChecker.php',
        'UpdateURLParams' => __DIR__ . '../../functions/updateURLParams.php',
    ],


    'Controllers' => [
        'Carousel' => __DIR__ . '../../controllers/CarouselController.php',
        'Login' => __DIR__ . '../../controllers/LoginController.php',
        'Course' => __DIR__ . '../../controllers/CourseController.php',
        'Logout' => __DIR__ . '../../controllers/LogoutController.php',
        'Program' => __DIR__ . '../../controllers/ProgramController.php',
        'Section' => __DIR__ . '../../controllers/SectionController.php',
        'Subject' => __DIR__ . '../../controllers/SubjectController.php',
        'User' => __DIR__ . '../../controllers/UserController.php',
        'AcademicPeriod' => __DIR__ . '../../controllers/AcademicPeriodController.php',
    ],

    'Models' => [
        'Carousel' => __DIR__ . '../../models/Carousel.php',
        'Course' => __DIR__ . '../../models/Course.php',
        'Program' => __DIR__ . '../../models/Program.php',
        'Section' => __DIR__ . '../../models/Section.php',
        'Subject' => __DIR__ . '../../models/Subject.php',
        'User' => __DIR__ . '../../models/User.php',
        'AcademicPeriod' => __DIR__ . '../../models/AcademicPeriod.php',
    ],

    'Partials' => [
        'System' => [
            'Toast' => __DIR__ . '../../views/partials/public/alert_Toast.php',
            'Catalog' => [
                'Card' => __DIR__ . '../../views/partials/public/catalog_view/card.php',
            ],
        ],
        'User' => [
            'Head' => __DIR__ . '../../views/partials/user/head.php',
            'Footer' => __DIR__ . '../../views/partials/user/footer.php',
            'Navbar' => __DIR__ . '../../views/partials/user/navbar.php',
            'Sidebar' => __DIR__ . '../../views/partials/user/sidebar.php',
            'SideBarData' => __DIR__ . '../../views/partials/user/sidebar-data.php',
            'Carousel' => __DIR__ . '../../views/partials/user/usercarousel.php',
            'Calendar' => __DIR__ . '../../views/partials/user/mycalendar.php',
            'Tasks' => __DIR__ . '../../views/partials/user/mytasks.php',
            'Announcements' => __DIR__ . '../../views/partials/user/announcements.php',
            'WidgetPanel' => __DIR__ . '../../views/partials/user/widgetpanel.php',
            'Courses' => __DIR__ . '../../views/partials/user/mycourses.php',
        ],
        'HighLevel' => [
            'LiveCount' => __DIR__ . '../../views/partials/high-level/livecount.php',
            'Modals' => [
                'Course' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addCourse.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'Program' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addProgram.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'Role' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addRole.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'Section' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addSection.php',
                    'Config' => __DIR__ . '../../views/partials/high-level/modal_configSection.php',
                    'Details' => __DIR__ . '../../views/partials/high-level/modal_detailsSection.php',
                ],
                'Subject' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addSubject.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'User' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addUser.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'Academic' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addAcademicPeriod.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
                'Department' => [
                    'Add' => __DIR__ . '../../views/partials/high-level/modal_addDepartment.php',
                    // 'Config' => __DIR__ . '../../views/partials/high-level/modal_configCourse.php',
                ],
            ],
            'Dragger' => [
                'Carousel' => [
                    'Home' => __DIR__ . '../../views/partials/high-level/draggable_homeCarousel.php',
                ],
            ],
        ],
        'Widgets' => [
            'Card' => __DIR__ . '../../views/partials/public/widget_card.php',
            'SearchUser' => __DIR__ . '../../views/partials/public/widget_searchUser.php',
            'DataTable' => __DIR__ . '../../views/partials/public/widget_dataTable.php',
        ]
    ],

    'Pages' => [
        'Admin' => [
            'Dashboard' => __DIR__ . '../../views/users/admin/dashboard_admin.php',
            'Courses' => __DIR__ . '../../views/users/admin/courses_admin.php',
            'Programs' => __DIR__ . '../../views/users/admin/programs_admin.php',
            'Sections' => __DIR__ . '../../views/users/admin/section_admin.php',
            'Subjects' => __DIR__ . '../../views/users/admin/subjects_admin.php',
            'Users' => __DIR__ . '../../views/users/admin/users_admin.php',
            'Content' => __DIR__ . '../../views/users/admin/content_admin.php',
            'AcademicTerm' => __DIR__ . '../../views/users/admin/academic_calendar_admin.php',
            'GeneralLogs' => __DIR__ . '../../views/users/admin/academic_calendar_admin.php',
        ],
        'Level Coordinator' => [
            'Dashboard' => __DIR__ . '../../views/users/level_coordinator/dashboard_level_coordinator.php',
        ],
        'Student' => [
            'Dashboard' => __DIR__ . '../../views/users/students/dashboard_student.php',
        ],
        'Teacher' => [
            'Dashboard' => __DIR__ . '../../views/users/teachers/dashboard_teacher.php',
        ],
    ],
]);

function asset($path)
{
    return BASE_PATH_LINK . 'src/assets/' . ltrim($path, '/');
}

function route($path) {}
