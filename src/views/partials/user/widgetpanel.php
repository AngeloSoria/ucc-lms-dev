<?php
require_once 'widget-user-data.php';

// Get the user role from the session
$userRole = $_SESSION['role'];

// Check if the role exists in the user_widgets array
$widgetsToInclude = $user_widgets[$userRole] ?? [];

?>
<div id="widgetPanel">
    <?php
    // Loop through each widget assigned to the user role
    foreach ($widgetsToInclude as $widgetKey) {
        // Check if the widget key exists in available_widgets
        if (isset($available_widgets[$widgetKey])) {
            // Include the widget file
            require_once $available_widgets[$widgetKey];
        }
    }
    ?>
</div>