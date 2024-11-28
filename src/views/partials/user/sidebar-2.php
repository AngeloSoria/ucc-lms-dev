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
        <!-- <li class="border border-top-0 submenu-main">
            <a href="javascript:void(0);" class="sidebar-item submenu-toggle">
                <div class="sidebar-item-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="sidebar-item-title">
                    asdasda
                </div>
                <div class="dropdownIcon">
                    <i class="icon bi bi-chevron-left fs-6 ms-auto fw-medium"></i>
                </div>
            </a>
            <div class="submenu submenu-content transition-1">
                <ul class="ul_no-design">
                    <li>
                        <a href="#"
                            class="d-flex gap-3 py-3 pe-2 submenu-item" style=" padding-left: 2rem;">
                            <i class="bi bi-people"></i>
                            <span class="submenu-item-text">
                                qq
                            </span>
                        </a>
                    </li>
                    <hr class="p-0 m-0">
                    <li>
                        <a href="#"
                            class="d-flex gap-3 py-3 pe-2 submenu-item" style="padding-left: 2rem;">
                            <i class="bi bi-journal-text"></i>
                            <span class="submenu-item-text">
                                qweqwe
                            </span>
                        </a>
                    </li>

                </ul>
            </div>
        </li> -->
        <li class="border border-top-0">
            <a href="<?php echo BASE_PATH_LINK ?>"
                class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-columns-gap" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Overview
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#"
                class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-folder2" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Subject Modules
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#"
                class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Assignments
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#"
                class="sidebar-item">
                <div class="sidebar-item-icon">
                    <i class="bi bi-people" aria-hidden="true"></i>
                </div>
                <div class="sidebar-item-title">
                    Teachers
                </div>
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#"
                class="sidebar-item">
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
                <a href="<?php echo BASE_PATH_LINK . 'src/views/users/viewprofile.php?viewProfile=' . $_SESSION['user_id'] ?>" class="sidebar-item <?= $CURRENT_PAGE == $key ? 'active' : '' ?>">
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