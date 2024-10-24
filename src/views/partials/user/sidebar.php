<?php
include_once(FILE_PATHS['Partials']['User']['SideBarData']);
?>

<div class="sidebar bg-light shadow-sm py-1 border z-2" id="sidebarMenu">
    <ul class="p-0 ul_no-design">
        <?php
        // Role based sidebar
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role']; // Get the role from the session
            // echo '<script>console.log("' . $role . '")</script>';
            if (isset($sidebar_content[$role])) {
                // Loop through the sidebar content for the current role
                foreach ($sidebar_content[$role] as $key => $item) {
        ?>
                    <li class="border border-top-0">
                        <a href="<?php echo $item['link']; ?>" class="d-flex gap-3 sidebar-item <?php if ($CURRENT_PAGE == $key) { echo 'active'; } ?>">
                            <i class="bi <?php echo $item['icon']; ?>" aria-hidden="true"></i>
                            <?php echo $item['title']; ?>
                        </a>
                    </li>
        <?php
                }
            }
        }
        ?>

    </ul>
</div>
<!-- End of Sidebar -->