<?php
require_once '../../../src/config/connection.php'; // Include the database connection class
// require_once 'src/config/connection.php'; // Include the database connection class

// Create an instance of DBConnection
$dbConnection = new Database();

// Get the PDO connection
$pdo = $dbConnection->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['userID'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $email = $_POST['email'] ?? null;
    $user_type = $_POST['user_type'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $middle_name = $_POST['middle_name'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $birth_date = $_POST['birth_date'] ?? null;
    $created_by = "system"; // Replace with the actual creator's username

    // Simple validation
    if (!$userID || !$username || !$password || !$email || !$user_type || !$first_name || !$last_name || !$birth_date) {
        die("Error: Required fields are missing.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO users (user_id, username, password, email, phone, user_type, first_name, last_name, middle_name, prefix, gender, birth_date, created_by, isActive)
            VALUES (:userID, :username, :password, :email, :user_type, :first_name, :last_name, :middle_name, :prefix, :gender, :birth_date, :created_by, 1)
        ");

        // Execute SQL statement
        $stmt->execute([
            'userID' => $userID,
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email,
            'user_type' => $user_type,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name,
            'prefix' => $prefix,
            'gender' => $gender,
            'birth_date' => $birth_date,
            'created_by' => $created_by
        ]);

        echo "User registered successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!-- THIS FILE IS TEMPORARY -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
</head>

<body>
    <h2>Register</h2>
    <form id="registrationForm" method="POST">
        <label for="userID">User ID:</label>
        <input type="text" id="userID" name="userID" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="middle_name">Middle Name:</label>
        <input type="text" id="middle_name" name="middle_name"><br>

        <label for="prefix">Prefix:</label>
        <input type="text" id="prefix" name="prefix"><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br>

        <label for="birth_date">Birth Date:</label>
        <input type="date" id="birth_date" name="birth_date" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>

        <!-- <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" placeholder="+63 ### ### ####"><br> -->

        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="registrar">Registrar</option>
            <option value="admission">Admission</option>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
        </select><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" readonly><br>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" readonly><br>

        <button type="submit">Register</button>
    </form>

    <!-- Link to external JavaScript file -->
    <script>
        // register_user.js
        document.addEventListener('DOMContentLoaded', () => {
            const userIDInput = document.getElementById('userID');
            const lastNameInput = document.getElementById('last_name');
            const birthDateInput = document.getElementById('birth_date');

            function generateCredentials() {
                const userID = userIDInput.value;
                const lastName = lastNameInput.value.toLowerCase();
                const birthDate = new Date(birthDateInput.value);

                console.log('UserID:', userID);
                console.log('Last Name:', lastName);
                console.log('Birth Date:', birthDate);

                const birthMonth = ('0' + (birthDate.getMonth() + 1)).slice(-2);
                const birthDay = ('0' + birthDate.getDate()).slice(-2);
                const birthYear = birthDate.getFullYear();

                if (userID && lastName && birthDateInput.value) {
                    // Generate username
                    const username = `${lastName}${userID}`;
                    console.log('Generated Username:', username);
                    document.getElementById('username').value = username;

                    // Generate password
                    const password = `${lastName}${birthMonth}${birthDay}${birthYear}`;
                    console.log('Generated Password:', password);
                    document.getElementById('password').value = password;
                }
            }

            // Attach event listeners to the inputs
            userIDInput.addEventListener('input', generateCredentials);
            lastNameInput.addEventListener('input', generateCredentials);
            birthDateInput.addEventListener('change', generateCredentials);
        });
    </script>

</body>

</html>