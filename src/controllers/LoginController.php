<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);
require_once(FILE_PATHS['Models']['Uploads']);

require_once MODELS . 'SessionManager.php';

class LoginController
{
    public function login()
    {
        session_start(); // Start session here before any logic.

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Capture username and password from POST data
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Database connection
            $db = new Database();
            $pdo = $db->getConnection();

            // Instantiate the controllers and models
            $generalLogsController = new GeneralLogsController();

            // Fetch user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status = 'active'");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID after login to prevent session fixation
                session_unset(); // Clear previous session variables
                session_regenerate_id(true); // Regenerate session ID

                // Set session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];


                if (isset($_ENV['SESSION_LOCK_ENABLED']) && $_ENV['SESSION_LOCK_ENABLED'] == 'true') {
                    $sessionManagerModel = new SessionManager();
                    // Check session lock before logging in
                    if (!$sessionManagerModel->setUserSessionLock()) {
                        session_unset();  // Unset session if lock fails
                        $_SESSION['SESSION_LOCK_ERR'] = true;
                        header('Location: ' . BASE_PATH_LINK);  // Redirect to login page
                        exit;
                    }

                    // Check if session has expired
                    if (!$sessionManagerModel->checkSessionExpiry()) {
                        // Session expired, force logout and redirect to login
                        session_unset();
                        $_SESSION['SESSION_EXPIRED_ERR'] = true;
                        header('Location: ' . BASE_PATH_LINK);
                        exit;
                    }
                }

                // Add login logs to db
                $result_login = $generalLogsController->addLog_LOGIN($_SESSION['user_id'], $_SESSION['role']);
                if (!$result_login['success']) {
                    msgLog('ERROR', 'Failed to log login activity for user ID: ' . $_SESSION['user_id']);
                }

                // Convert BLOB to base64 for profile picture
                if (!empty($user['profile_pic'])) {
                    $_SESSION['profile_pic'] = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
                } else {
                    $_SESSION['profile_pic'] = 'img/avatars/default-profile.png'; // Default image
                }

                // Redirect based on role
                switch ($user['role']) {
                    case 'Admin':
                        header('Location: src/views/users/admin/dashboard_admin.php');
                        break;
                    case 'Level Coordinator':
                        header('Location: src/views/users/level_coordinator/dashboard_level_coordinator.php');
                        break;
                    case 'Teacher':
                        header('Location: src/views/users/teacher/dashboard_teacher.php');
                        break;
                    case 'Student':
                        header('Location: src/views/users/student/dashboard_student.php');
                        break;
                    default:
                        msgLog('[ERROR]', 'Invalid user role: ' . $user['role']);
                        header('Location: ' . BASE_PATH_LINK);
                        break;
                }
                exit; // Always exit after redirection
            } else {
                // If authentication fails
                $_SESSION['LOGIN_INVALID'] = true;
                header('Location: ' . BASE_PATH_LINK);
                exit;
            }
        }
    }
}
