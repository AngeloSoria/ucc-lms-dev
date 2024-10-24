<?php
// controllers/LoginController.php

require_once(__DIR__ . '../../config/connection.php');

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

                // Convert BLOB to base64
                if (!empty($user['profile_pic'])) {
                    $_SESSION['profile_pic'] = 'data:image/jpeg;base64,' . base64_encode($user['profile_pic']);
                } else {
                    // Set a default image if the profile picture is not set
                    $_SESSION['profile_pic'] = '../../assets/images/default-profile.png'; // path to default image
                }

                // Redirect based on role
                switch ($user['role']) {
                    case 'superadmin':
                        header('Location: src/views/users/superadmin/dashboard_superadmin.php');
                        break;
                    case 'Admin':
                        header('Location: src/views/users/admin/dashboard_admin.php');
                        break;
                    case 'Level Coordinator':
                        header('Location: src/views/users/registrar/dashboard_admin.php');
                        break;
                    case 'Teacher':
                        header('Location: src/views/users/teacher/dashboard_teacher.php');
                        break;
                    case 'Student':
                        header('Location: src/views/users/student/dashboard_student.php');
                        break;
                    default:
                        header('Location: src/views/home.php');
                        break;
                }
            } else {
                // echo "Invalid login credentials.";
            }
        }
    }
}
