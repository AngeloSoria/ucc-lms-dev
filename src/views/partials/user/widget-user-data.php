<?php

// This dictionary contains the available widgets per role.
$available_widgets = [
    // Key => Widget File
    "Calendar" => "mycalendar.php",
    "Tasks" => "mytasks.php",
    "Announcements" => "announcements.php",
    "Upcoming" => "upcoming.php",
];

$user_widgets = [
    // Role => [Keys, ...]
    'Admin' => ["Calendar", "Announcements"],
    'Teacher' => ["Calendar", "Tasks", "Announcements", "Upcoming"],
    'Student' => ["Calendar", "Tasks", "Announcements", "Upcoming"],
    'Level Coordinator' => ["Calendar", "Tasks", "Announcements"]
];
