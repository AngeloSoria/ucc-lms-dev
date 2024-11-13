<nav class="c-navbar shadow-sm">
    <div class="nav-container">
        <section class="container-left">
            <!-- Sidebar toggler -->
            <button class="border border-0 bg-transparent" id="btnSideBarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Logo on the left -->
            <a class="navbar-brand" href="<?php
                                            // Check user role and set the appropriate dashboard link
                                            if (isset($_SESSION['role'])) {
                                                switch ($_SESSION['role']) {
                                                    case 'Admin':
                                                        echo 'dashboard_admin.php'; // Change to your admin dashboard path
                                                        break;
                                                    case 'Teacher':
                                                        echo 'dashboard_teacher.php'; // Change to your teacher dashboard path
                                                        break;
                                                    case 'Level Coordinator':
                                                        echo 'dashboard_level_coordinator.php'; // Change to your teacher dashboard path
                                                        break;
                                                    case 'Student':
                                                        echo 'dashboard_student.php'; // Change to your student dashboard path
                                                        break;
                                                    default:
                                                        header('Location: ' . BASE_PATH_LINK); // Fallback to home if role is unknown
                                                        break;
                                                }
                                            } else {
                                                header('Location: ' . BASE_PATH_LINK); // Redirect to home if no role is set
                                            }
                                            ?>">
                <img src="<?php echo asset('img/ucc-logo.png'); ?>" alt="UCC Logo" class="d-inline-block align-text-top" />
            </a>
        </section>

        <!-- Right view -->
        <section class="container-right" id="navbarNav">
            <div class="nav-items">
                <!-- Notifications Icon -->
                <div class="nav-item">
                    <a id="navNotification" href="#" title="Notifications" class="nav-link nav-link-icon" role="button">
                        <i class="bi bi-bell"></i> <!-- Bell icon for notifications -->
                        <span id="unreadIndicator" class="nav-item-unread-indicator"></span>
                    </a>
                </div>

                <!-- Mail Icon -->
                <div class="nav-item">
                    <a id="navEmail" href="#" title="Messages" class="nav-link nav-link-icon">
                        <i class="bi bi-envelope"></i> <!-- Envelope icon for mail -->
                        <span id="unreadIndicator" class="nav-item-unread-indicator"></span>
                    </a>
                </div>

                <span class="nav-mobile_line"
                    style="height: 30px; width: 1.5px; background-color: black; opacity: 0.35;"></span>

                <!-- User Info Dropdown -->
                <div class="nav-item nav-mobile_userinfo dropdown">
                    <a class="nav-link dropdown-toggle d-flex gap-2 justify-content-center align-items-center"
                        href="javascript:void(0);" id="userDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <!-- User Profile Picture -->
                        <?php if (isset($_SESSION['profile_pic'])): ?>
                            <img src="<?php echo $_SESSION['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle"
                                width="30" height="30">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-5"></i>
                        <?php endif; ?>

                        <!-- Username and Role -->
                        <?php
                        echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) . ' (' . htmlspecialchars($_SESSION['role']) . ')';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">My Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../../../controllers/LogoutController.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</nav>
<script src="<?php echo asset('js/user-navbar.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo asset('css/user-main_responsive.css') ?>">