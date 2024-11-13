<?php
include_once(FILE_PATHS['Partials']['User']['SideBarData']);
$user_sidebar_data = $sidebar_content[$_SESSION['role']];

// Get All Enrolled Subjects from User (Teacher, Student)
$fakedata_enrolled_subjects = [
    [
        'subject_id' => 3001,
        'subject_code' => 'ITMA1223',
        'subject_name' => 'Data Structures & Algorithms',
    ],
    [
        'subject_id' => 3002,
        'subject_code' => 'PHYS4412',
        'subject_name' => 'Physical Education 2',
    ],
    [
        'subject_id' => 3003,
        'subject_code' => 'LERP1337',
        'subject_name' => 'Information Assurance and Security (Data Privacy)',
    ],
    [
        'subject_id' => 3004,
        'subject_code' => 'MATH1124',
        'subject_name' => 'Calculus II',
    ],
    [
        'subject_id' => 3005,
        'subject_code' => 'CHEM2011',
        'subject_name' => 'Organic Chemistry',
    ],
    [
        'subject_id' => 3006,
        'subject_code' => 'HIST3010',
        'subject_name' => 'World History',
    ],
    [
        'subject_id' => 3007,
        'subject_code' => 'PSYC2210',
        'subject_name' => 'Introduction to Psychology',
    ],
    [
        'subject_id' => 3008,
        'subject_code' => 'CSCI1101',
        'subject_name' => 'Introduction to Computer Science',
    ],
];

?>

<div class="sidebar bg-light shadow-sm py-1 border z-2" id="sidebarMenu">
    <div class="sidebar-controls">
        <div class="controls">
            <button class="btn btn-close" id="btnSideBarMenu2" onclick="toggleSidebar();"></button>
        </div>
    </div>
    <ul class="p-0 ul_no-design">
        <?php foreach ($user_sidebar_data as $key => $single_user): ?>
            <?php if (isset($single_user['isGroup']) && isset($single_user['sublinks'])): ?>
                <li class="border border-top-0 submenu-main">
                    <a href="javascript:void(0);" class="sidebar-item submenu-toggle">
                        <div class="sidebar-item-icon">
                            <i class="bi <?= htmlspecialchars($single_user['icon']) ?>"></i>
                        </div>
                        <div class="sidebar-item-title">
                            <?= htmlspecialchars($single_user['title']) ?>
                        </div>
                        <div class="dropdownIcon">
                            <i class="icon bi bi-chevron-left fs-6 ms-auto fw-medium <?= in_array($CURRENT_PAGE, array_keys($single_user['sublinks'])) ? 'rotate' : '' ?>"></i>
                        </div>
                    </a>
                    <div class="submenu submenu-content transition-1 <?= in_array($CURRENT_PAGE, array_keys($single_user['sublinks'])) ? 'submenu-active' : '' ?>">
                        <ul class="ul_no-design">
                            <?php foreach ($single_user['sublinks'] as $sublinks => $sublink): ?>
                                <li>
                                    <a href="<?= isset($sublink['link']) ? $sublink['link'] : '#' ?>"
                                        class="d-flex gap-3 py-3 pe-2 submenu-item <?= $CURRENT_PAGE == $sublinks ? 'active' : '' ?>" style="padding-left: 2rem;">
                                        <i class="bi <?= htmlspecialchars($sublink['icon']) ?>"></i>
                                        <span class="submenu-item-text">
                                            <?= htmlspecialchars($sublink['title']) ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <hr class="p-0 m-0">
                            <?php
                            // load enrolled subjects.
                            $RETRIEVED_ENROLLED_SUBJECTS = $fakedata_enrolled_subjects;
                            if ($single_user['title'] === 'Subjects'):
                                foreach ($RETRIEVED_ENROLLED_SUBJECTS as $subject):
                            ?>
                                    <li>
                                        <a href="<?= "enrolled_subjects.php?subject_id=" . $subject['subject_id'] ?>"
                                            class="d-flex gap-3 py-3 pe-2 submenu-item" style="padding-left: 2rem;">
                                            <i class="bi bi-journal-text"></i>
                                            <span class="submenu-item-text">
                                                <?= htmlspecialchars($subject['subject_name']) ?>
                                            </span>
                                        </a>
                                    </li>
                            <?php endforeach;
                            endif; ?>


                        </ul>
                    </div>
                </li>
            <?php else: ?>
                <li class="border border-top-0">
                    <a href="<?= isset($single_user['link']) ? $single_user['link'] : '#' ?>"
                        class="sidebar-item <?= $CURRENT_PAGE == $key ? 'active' : '' ?>">
                        <div class="sidebar-item-icon">
                            <i class="bi <?= htmlspecialchars($single_user['icon']) ?>" aria-hidden="true"></i>
                        </div>
                        <div class="sidebar-item-title">
                            <?= htmlspecialchars($single_user['title']) ?>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <!-- mobile view -->
    <div class="p-0" id="mobile-prerender">
        <hr>
        <h6 class="px-3">Others</h6>
        <ul class="p-0 ul_no-design">
            <li class="border border-top-0">
                <a href="#" class="sidebar-item <?= $CURRENT_PAGE == $key ? 'active' : '' ?>">
                    <div class="sidebar-item-icon">
                        <i class="bi bi-person" aria-hidden="true"></i>
                    </div>
                    <div class="sidebar-item-title">
                        My Profile
                    </div>
                </a>
            </li>
            <li class="border border-top-0">
                <a href="../../../../src/controllers/LogoutController.php" class="sidebar-item">
                    <div class="sidebar-item-icon">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    </div>
                    <div class="sidebar-item-title">
                        Logout
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<script src="<?php echo asset('js/submenu-handler.js') ?>"></script>
<!-- End of Sidebar -->