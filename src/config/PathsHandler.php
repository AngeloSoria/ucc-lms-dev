<?php


define('BASE_PATH', __DIR__ . '../../../');
define('BASE_PATH_LINK','/ucc-lms-dev/');


// Load environment variables from .env file
define('AUTO_LOAD', BASE_PATH . 'vendor/autoload.php');


define('UPLOAD_PATH', [
    'System' => __DIR__ . '../../../src/uploads/system',
    'User' => __DIR__ . '../../../src/uploads/user',
]);

define('FILE_PATHS', [
    'DATABASE' => __DIR__ . '/connection.php',

    'Controllers' => [
        'Carousel' => __DIR__ . '../../controllers/CarouselController.php',
        'Login' => __DIR__ . '../../controllers/LoginController.php',
        'Course' => __DIR__ . '../../controllers/CourseController.php',
        'Logout' => __DIR__ . '../../controllers/LogoutController.php',
        'Program' => __DIR__ . '../../controllers/ProgramController.php',
        'Section' => __DIR__ . '../../controllers/SectionController.php',
        'Subject' => __DIR__ . '../../controllers/SubjectController.php',
        'User' => __DIR__ . '../../controllers/UserController.php',
    ],

    'Models' => [
        'Carousel' => __DIR__. '../../models/Carousel.php',
        'Course' => __DIR__. '../../models/Course.php',
        'Program' => __DIR__. '../../models/Program.php',
        'Section' => __DIR__. '../../models/Section.php',
        'Subject' => __DIR__. '../../models/Subject.php',
        'User' => __DIR__. '../../models/User.php',
    ],

    'Partials' => [
        'System' => [
            'Toast' => __DIR__. '../../views/partials/public/alert_Toast.php',
        ],
        'User' => [
            'Head' => __DIR__. '../../views/partials/user/head.php',
            'Footer' => __DIR__. '../../views/partials/user/footer.php',
            'Navbar' => __DIR__. '../../views/partials/user/navbar.php',
            'Sidebar' => __DIR__. '../../views/partials/user/sidebar.php',
            'SideBarData' => __DIR__. '../../views/partials/user/sidebar-data.php',
            'Carousel' => __DIR__. '../../views/partials/user/usercarousel.php',
            'Calendar' => __DIR__. '../../views/partials/user/mycalendar.php',
            'Tasks' => __DIR__. '../../views/partials/user/mytasks.php',
        ],
        'HighLevel' => [
            'LiveCount' => __DIR__. '../../views/partials/high-level/livecount.php',
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
            ],
            'Sortable' => [
                'Carousel' => [
                    'Home' => __DIR__ . '../../views/partials/high-level/sortable_homeCarousel.php',
                ],
            ],
        ],
    ],

    'Pages' => [
        'Admin' => [
            'Dashboard' => __DIR__. '../../views/users/admin/dashboard_admin.php',
            'Courses' => __DIR__. '../../views/users/admin/courses_admin.php',
            'Programs' => __DIR__. '../../views/users/admin/programs_admin.php',
            'Sections' => __DIR__. '../../views/users/admin/sections_admin.php',
            'Subjects' => __DIR__. '../../views/users/admin/subjects_admin.php',
            'Users' => __DIR__. '../../views/users/admin/users_admin.php',
        ],
        'Level Coordinator' => [
            'Dashboard' => __DIR__. '../../views/users/level_coordinator/dashboard_levelCoordinator.php',
        ],
        'Registrar' => [],
        'Students' => [],
        'Teachers' => [],
    ],
]);

