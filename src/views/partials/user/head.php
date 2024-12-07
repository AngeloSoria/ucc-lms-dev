<?php
require_once MODELS . 'SessionManager.php';

// Ensure that the user is logged in
if (isset($_SESSION['user_id'])) {
    // Assuming the user ID is stored in session and session_id() is the PHP session ID
    $userId = $_SESSION['user_id'];
    $sessionId = session_id();

    // Create an instance of SessionManager (make sure the DB connection is passed)
    $sessionManager = new SessionManager();

    // Check if the session has expired
    if (!$sessionManager->checkSessionExpiry() || !$sessionManager->userHasSession($userId)) {
        // Session has expired, log out the user or no session found.
        $_SESSION['SessionExpired'] = true;
        require_once CONTROLLERS . 'LogoutController.php';
        $logoutController = new LogoutController();
    }

    $sessionManager->updateLastActivity($userId, $sessionId);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo asset('img/favicon.png') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme/dist/select2-bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/user-main.css') ?>">

    <script src="<?php echo asset('js/jquery-3.6.0.min.js') ?>"></script>
    <script defer src="<?php echo asset('js/root.js') ?>"></script>
    <script defer src="<?php echo asset('js/DynamicFormEditData.js') ?>"></script>
    <script defer src="<?php echo asset('js/image-previewer.js') ?>"></script>

    <!-- Data Table JS -->
    <script defer src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- SelectJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/tftwj8ejo21qbbt7jzz53ityv0j42ooyr713pf6xrqsshstg/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script defer src="<?php echo asset("js/tinymce.js") ?>"></script>


    <title>LMS | Unida Christian College - Cavite</title>
</head>