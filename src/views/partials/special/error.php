<?php

require_once("../../../config/PathsHandler.php");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="text-center bg-white shadow-sm border p-5 rounded">
        <img src="<?php echo asset('img/ucc-logo.png') ?>" alt="">
        <h1 class="display-1 text-danger">404</h1>
        <h2 class="mb-4">Oops! Page Not Found</h2>
        <p class="lead mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="<?php echo BASE_PATH_LINK ?>" class="btn btn-success">Go Back Home</a>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>