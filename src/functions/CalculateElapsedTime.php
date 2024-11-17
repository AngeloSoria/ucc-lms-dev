<?php
function timeElapsedSince($lastLogin)
{
    $lastLoginDateTime = new DateTime($lastLogin);
    $currentDateTime = new DateTime();

    $interval = $lastLoginDateTime->diff($currentDateTime);

    $days = $interval->d;
    $hours = $interval->h;
    $minutes = $interval->i;

    $output = [];
    if ($days > 0) {
        $output[] = "$days day" . ($days > 1 ? "s" : "");
    }
    if ($hours > 0) {
        $output[] = "$hours hour" . ($hours > 1 ? "s" : "");
    }
    if ($minutes > 0) {
        $output[] = "$minutes minute" . ($minutes > 1 ? "s" : "");
    }

    return implode(" and ", $output);
}

// Example usage
// $lastLogin = '2024-11-16 13:00:00';
// echo timeElapsedSince($lastLogin); // Output: "1 day and 2 hours"
