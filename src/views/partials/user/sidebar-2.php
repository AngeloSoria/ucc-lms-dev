<?php
// include_once(FILE_PATHS['Partials']['User']['SideBarData']);
// $user_sidebar_data = $sidebar_content[$_SESSION['role']];

?>

<div class="sidebar bg-white shadow-sm py-1 border z-3" id="sidebarMenu">
    <div class="sidebar-controls">
        <div class="controls">
            <button class="btn btn-close" id="btnSideBarMenu2" onclick="toggleSidebar();"></button>
        </div>
    </div>
    <ul class="p-0 ul_no-design">
        <li class="border border-top-0">
            <a href="<?php echo BASE_PATH_LINK ?>" class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-columns-gap" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Overview
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id']]); ?>"
                class="sidebar-item <?php echo count($_GET) === 1 && isset($_GET['subject_section_id']) ? 'active' : '' ?>">
                <div class="sidebar-item-icon">
                    <i class="bi bi-folder2" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Subject Modules
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'assignments' => '1']) ?>" class="sidebar-item <?php echo isset($_GET['subject_section_id'], $_GET['assignments']) ? 'active' : '' ?>">
                <div class="sidebar-item-icon">
                    <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Assignments
                </div>
            </a>
        </li>

        <?php if (userHasPerms(['Teacher'])): ?>
            <li class="border border-top-0">
                <a href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'gradebook' => '1']) ?>" class="sidebar-item <?php echo isset($_GET['subject_section_id'], $_GET['gradebook']) ? 'active' : '' ?>">
                    <div class="sidebar-item-icon">
                        <i class="bi bi-journal-text" aria-hidden="true"></i>
                    </div>
                    <div class="sidebar-item-title">
                        Gradebook
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <li class="border border-top-0">
            <a href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'announcements' => '1']) ?>" class="sidebar-item <?php echo isset($_GET['subject_section_id'], $_GET['announcements']) ? 'active' : '' ?>">
                <div class="sidebar-item-icon">
                    <i class="bi bi-megaphone" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Announcements
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#" class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-people" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Teachers
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#" class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-people" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Students
                </div>
            </a>
        </li>
    </ul>
    <!-- mobile view -->
    <div class="p-0" id="mobile-prerender">
        <hr>
        <h6 class="px-3">Others</h6>
        <ul class="p-0 ul_no-design">
            <li class="border border-top-0">
                <a href="<?php echo BASE_PATH_LINK . 'src/views/users/viewprofile.php?viewProfile=' . $_SESSION['user_id'] ?>"
                    class="sidebar-item <?= $CURRENT_PAGE == $key ? 'active' : '' ?>">
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