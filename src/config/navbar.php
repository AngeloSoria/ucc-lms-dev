<?php
// session_start(); // Start the session
// other code...
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm border-5 border-bottom border-success">
    <div class="container-fluid px-5 d-flex align-items-center" style="width: 95%">

        <section class="d-flex align-items-center justify-content-center gap-4">
            <!-- Sidebar toggler -->
            <button class="border border-0 bg-transparent" id="btnSideBarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Logo on the left -->
            <a class="navbar-brand" href="<?php echo $_ROOT_PATH ?>">
                <img src="../../../assets/images/icons/ucc-logo.png" alt="Logo" width="90" class="d-inline-block align-text-top" />
            </a>
        </section>

        <!-- Right view -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav d-flex gap-5 align-items-center">
                <!-- Notifications Icon -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="#">
                        <i class="bi bi-bell fs-5"></i> <!-- Bell icon for notifications -->
                        <span class="position-absolute top-20 start-100 translate-middle badge rounded-pill bg-danger">
                            +99
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                </li>

                <!-- Mail Icon -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-envelope fs-5"></i> <!-- Envelope icon for mail -->
                    </a>
                </li>

                <span style="height: 40px; width: 1.5px; background-color: black; opacity: 0.35;"></span>

                <!-- User Info Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex gap-2 justify-content-center align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- User Profile Picture -->
                        <?php if (isset($_SESSION['profile_pic'])): ?>
                            <img src="<?php echo $_SESSION['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle" width="30" height="30">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-5"></i>
                        <?php endif; ?>
                        <!-- Username and Role -->
                        <?php echo htmlspecialchars($_SESSION['first_name']) . ' (' . htmlspecialchars($_SESSION['role']) . ')'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../../../controllers/LogoutController.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>