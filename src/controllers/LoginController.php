<?php
// controllers/LoginController.php
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);


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

            // Fetch user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status = 'active'");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($user && password_verify($password, $user['password'])) {

                // Set session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];

                // Add login logs to db.
                $generalLogsController->addLog_LOGIN($_SESSION['user_id'], $_SESSION['role']);

                // Convert BLOB to base64
                if (!empty($user['profile_pic'])) {
                    $_SESSION['profile_pic'] = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
                } else {
                    // Set a default image if the profile picture is not set
                    $_SESSION['profile_pic'] = asset('img/avatars/default-profile.png'); // path to default image
                }

                msgLog(
                    'INFO',
                    sprintf(
                        "[%s] [%s] [%s] [%s]",
                        "LOGIN",
                        $_SESSION['user_id'],
                        $_SESSION['username'],
                        $_SESSION['role']
                    ),
                );

                // Redirect based on role
                switch ($user['role']) {
                    case 'Admin':
                        header('Location: src/views/users/admin/dashboard_admin.php');
                        break;
                    case 'Level Coordinator':
                        header('Location: src/views/users/level_coordinator/dashboard_level_coordinator.php');
                        break;
                    case 'Teacher':
                        header('Location: src/views/users/teachers/dashboard_teacher.php');
                        break;
                    case 'Student':
                        header('Location: src/views/users/students/dashboard_student.php');
                        break;
                    default:
                        msgLog('[ERROR]', 'Something went wrong when trying to identify the user role.');
                        return false;
                }
            } else {
                return false;
            }
        }
    }
}
