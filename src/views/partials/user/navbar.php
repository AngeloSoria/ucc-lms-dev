<?php
// Fetch notifications
$db = new Database();
$pdo = $db->getConnection();
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<nav class="c-navbar shadow-sm">
    <div class="nav-container">
        <section class="container-left">
            <!-- Sidebar toggler -->
            <button class="border border-0 bg-transparent" id="btnSideBarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Logo on the left -->
            <a class="navbar-brand" href="<?php echo BASE_PATH_LINK ?>">
                <img src="<?php echo asset('img/ucc-logo.png'); ?>" alt="UCC Logo" class="d-inline-block align-text-top" />
            </a>
        </section>

        <!-- Right view -->
        <section class="container-right" id="navbarNav">
            <div class="nav-items">
                <!-- Notifications Icon -->
                <div class="nav-item dropdown position-relative">
                    <a id="navNotification" href="#" title="Notifications" class="nav-link nav-link-icon dropdown-toggle"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell position-relative">
                            <span id="unreadIndicator" class="nav-item-unread-indicator"></span>
                        </i> <!-- Bell icon for notifications -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="navNotification">
                        <li class="dropdown-header">Notifications</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <!-- <div id="notif-container">
                            </div> -->
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <?php
                                $stmt = $pdo->prepare(
                                    "SELECT
                                                m.module_id,
                                                ss.subject_section_id
                                            FROM notifications AS n
                                            JOIN contents AS c ON c.content_id = :content_id
                                            JOIN modules AS m ON c.module_id = m.module_id
                                            JOIN subject_section AS ss ON m.subject_section_id = ss.subject_section_id
                                            WHERE n.user_id = :user_id LIMIT 1;"
                                );
                                $stmt->bindParam(":content_id", $notification['content_id']);
                                $stmt->bindParam(":user_id", $_SESSION['user_id']);
                                $stmt->execute();
                                $notifItemInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <!-- Example Notification Items -->
                                <li>
                                    <a href="<?php echo BASE_PATH_LINK . 'src/views/users/' . lcfirst($_SESSION['role']) . '/subject_view.php?subject_section_id=' . $notifItemInfo['subject_section_id'] . '&module_id=' . $notifItemInfo['module_id'] . '&content_id=' . $notification['content_id'] ?>" class="dropdown-item">
                                        <i class="bi <?php echo getBootstrapIcon('assignment') ?> fs-5 text-critical"></i>
                                        <div>
                                            <span class="fw-bold text-wrap">
                                                <?php echo htmlspecialchars($notification['message']) ?>
                                            </span><br>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars(timeElapsed($notification['created_at'])) ?>
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <li class="p-2 text-center">
                                <p class="text-muted">No new notifications.</p>
                            </li>
                        <?php endif; ?>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="#" class="dropdown-item text-center">View All Notifications</a>
                        </li>
                    </ul>
                </div>

                <!-- Mail Icon -->
                <!-- <div class="nav-item">
                    <a id="navEmail" href="#" title="Messages" class="nav-link nav-link-icon">
                        <i class="bi bi-envelope"></i>
                        <span id="unreadIndicator" class="nav-item-unread-indicator"></span>
                    </a>
                </div> -->

                <span class="nav-mobile_line" style="height: 30px; width: 1.5px; background-color: black; opacity: 0.35;"></span>

                <!-- User Info Dropdown -->
                <div class="nav-item nav-mobile_userinfo dropdown">
                    <a class="nav-link dropdown-toggle d-flex gap-2 justify-content-center align-items-center"
                        href="javascript:void(0);" id="userDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <!-- User Profile Picture -->
                        <?php if (isset($_SESSION['profile_pic'])): ?>
                            <img src="<?php echo $_SESSION['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle object-fit-fill"
                                width="28" height="30">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-5"></i>
                        <?php endif; ?>

                        <!-- Username and Role -->
                        <?php
                        echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) . ' (' . htmlspecialchars($_SESSION['role']) . ')';
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH_LINK . 'src/views/users/viewprofile.php?viewProfile=' . $_SESSION['user_id'] ?>">My Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH_LINK . 'src/controllers/LogoutController.php' ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</nav>
<style>
    .nav-item-unread-indicator {
        position: absolute;
        top: 8px;
        right: 2px;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }

    .notifications-dropdown {
        max-height: 300px;
        overflow-y: auto;
        width: 500px;
    }

    .notifications-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
<script src="<?php echo asset('js/user-navbar.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo asset('css/user-main_responsive.css') ?>">