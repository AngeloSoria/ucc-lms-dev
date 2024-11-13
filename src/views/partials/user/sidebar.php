<?php
include_once(FILE_PATHS['Partials']['User']['SideBarData']);
$user_sidebar_data = $sidebar_content[$_SESSION['role']];
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
                            <i class="icon bi bi-chevron-left fs-6 ms-auto fw-medium"></i>
                        </div>
                    </a>
                    <div
                        class="submenu submenu-content transition-1 <?= in_array($CURRENT_PAGE, array_keys($single_user['sublinks'])) ? 'submenu-active' : '' ?>">
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