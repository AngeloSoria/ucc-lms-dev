<?php

require_once(__DIR__ . "../../../../config/PathsHandler.php");
require_once UTILS;

$ERROR_CODES = [
    "403" => [
        'Access Denied!',
        'You do not have permission to access this page. Please contact the administrator if you believe this is an error.'
    ],
    "404" => [
        'Oops! Page not found!',
        'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'
    ],
    "500" => [
        'Internal Server Error',
        'The server encountered an error and could not complete your request. Please try again later.'
    ],
    "401" => [
        'Unauthorized Access',
        'You must be logged in to view this page. Please log in and try again.'
    ]
];

$ERR_CODE = "000";
$MESSAGE = "NULL";
$TITLE = "NULL";

if (isset($_GET['err_code'])) {
    if (array_key_exists($_GET['err_code'], $ERROR_CODES)) {
        $ERR_CODE = $_GET['err_code'];
        $TITLE = $ERROR_CODES[$ERR_CODE][0];
        $MESSAGE = $ERROR_CODES[$ERR_CODE][1];
    }
} else {
    header("Location: " . BASE_PATH_LINK);
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<?php require FILE_PATHS['Partials']['User']['Head'] ?>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="container text-center bg-white shadow-sm border p-5 rounded">
        <img src="<?php echo asset('img/ucc-logo.png') ?>" alt="">
        <h1 class="display-1 text-danger"><?php echo sanitizeInput($ERR_CODE) ?></h1>
        <h2 class="mb-4"><?php echo sanitizeInput($TITLE) ?></h2>
        <p class="lead mb-4">

            <?php echo sanitizeInput($MESSAGE) ?>
        </p>
        <a href="<?php echo BASE_PATH_LINK ?>" class="btn btn-success">Go Back Home</a>
    </div>
</body>

</html>