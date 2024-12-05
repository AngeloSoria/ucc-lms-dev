<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);
require_once(FILE_PATHS['Models']['Uploads']);
require_once(FILE_PATHS['Models']['SessionLock']);


class LoginController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Database connection
            $db = new Database();
            $pdo = $db->getConnection();

            $generalLogsController = new GeneralLogsController();
            $sessionLockModel = new SessionLock();

            // Fetch user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status = 'active'");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID after login to prevent session fixation
                session_unset(); // Clear previous session variables
                session_regenerate_id(true);

                // Set session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];

                if ($sessionLockModel->setUserSessionLock() == false) {
                    session_unset();
                    $_SESSION['SESSION_LOCK_ERR'] = true;
                    header('Location: ' . BASE_PATH_LINK);
                    exit;
                }

                // Add login logs to db
                if (!$generalLogsController->addLog_LOGIN($_SESSION['user_id'], $_SESSION['role'])) {
                    msgLog('ERROR', 'Failed to log login activity for user ID: ' . $_SESSION['user_id']);
                }

                // Convert BLOB to base64 for profile picture
                if (!empty($user['profile_pic'])) {
                    $_SESSION['profile_pic'] = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
                } else {
                    // Set a default image if the profile picture is not set
                    $_SESSION['profile_pic'] = 'img/avatars/default-profile.png'; // Relative path
                }

                // Redirect based on role
                switch ($user['role']) {
                    case 'Admin':
                        header('Location: src/views/users/admin/dashboard_admin.php');
                        exit;
                    case 'Level Coordinator':
                        header('Location: src/views/users/level_coordinator/dashboard_level_coordinator.php');
                        exit;
                    case 'Teacher':
                        header('Location: src/views/users/teacher/dashboard_teacher.php');
                        exit;
                    case 'Student':
                        header('Location: src/views/users/student/dashboard_student.php');
                        exit;
                    default:
                        msgLog('[ERROR]', 'Invalid user role: ' . $user['role']);
                        header('Location: ' . BASE_PATH_LINK);
                        exit;
                }
            } else {
                return false;
            }
        }
    }
}
