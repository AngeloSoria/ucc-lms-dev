<?php
// Role based sidebar

?>
<div class="sidebar bg-light shadow-sm py-1 border z-2" id="sidebarMenu">
    <ul class="p-0 ul_no-design">
        <li class="border border-top-0">
            <a href="dashboard_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "dashboard") {
                echo "active";
            } ?>">
                <i class="bi bi-columns-gap" aria-hidden="true"></i>
                Dashboard
            </a>
        </li>

        <li class="border border-top-0">
            <a href="program_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "programs") {
                echo "active";
            } ?>">
                <i class="bi bi-person-rolodex" aria-hidden="true"></i>
                Programs
            </a>
        </li>

        <li class="border border-top-0">
            <a href="section_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "sections") {
                echo "active";
            } ?>">
                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                Sections
            </a>
        </li>

        <li class="border border-top-0">
            <a href="subjects_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "subjects") {
                echo "active";
            } ?>">
                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                Subjects
            </a>
        </li>

        <li class="border border-top-0">
            <a href="users_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "users") {
                echo "active";
            } ?>">
                <i class="bi bi-people" aria-hidden="true"></i>
                Users
            </a>
        </li>

        <!-- Accordion -->
        <!-- <li class="border border-top-0 submenu-main">
            <a href="#" class="d-flex gap-3 ps-4 pe-4 py-3 sidebar-item submenu-toggle">
                <i class="bi bi-people"></i>
                User Management
                <i class="bi bi-chevron-left fs-6 ms-auto fw-medium dropdownIcon"></i>
            </a>
            <div class="submenu transition-1 bg-dark-subtle p-0 submenu-content">
                <ul class="p-0 m-0 fs-6 ul_no-design">
                    <li>
                        <a href="#" class="d-flex gap-3 ps-5 py-3 submenu-item">
                            <i class="bi bi-people"></i>
                            Students
                        </a>
                    </li>
                    <li>
                        <a href="#" class="d-flex gap-3 ps-5 py-3 submenu-item">
                            <i class="bi bi-people"></i>
                            Teachers
                        </a>
                    </li>
                    <li>
                        <a href="#" class="d-flex gap-3 ps-5 py-3 submenu-item">
                            <i class="bi bi-people"></i>
                            Roles
                        </a>
                    </li>
                </ul>
            </div>
        </li> -->

        <li class="border border-top-0">
            <a href="departments_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "departments") {
                echo "active";
            } ?>">
                <i class="bi bi-building" aria-hidden="true"></i>
                Departments
            </a>
        </li>
        <li class="border border-top-0">
            <a href="content_admin.php" class="d-flex gap-3 sidebar-item 
            <?php if ($CURRENT_PAGE == "content") {
                echo "active";
            } ?>">
                <i class="bi bi-card-heading" aria-hidden="true"></i>
                Content
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#" class="d-flex gap-3 sidebar-item">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                Events Calendar
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#" class="d-flex gap-3 sidebar-item">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                School Year
            </a>
        </li>
        <li class="border border-top-0">
            <a href="#" class="d-flex gap-3 sidebar-item">
                <i class="bi bi-clipboard-data" aria-hidden="true"></i>
                General Logs
            </a>
        </li>

    </ul>

</div>
<!-- End of Sidebar -->