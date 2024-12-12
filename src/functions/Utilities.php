<?php
require_once __DIR__ . '../../../src/config/PathsHandler.php';
require_once(FILE_PATHS['Functions']['PHPLogger']);

require_once FUNCTIONS . 'UnixTimeStampManager.php';

function sanitizeInput($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function getBootstrapIcon($icon_name)
{
    $icon = [
        // General Categories
        "handout" => "bi-file-earmark-text-fill",
        "assignment" => "bi-clipboard-fill",
        "quiz" => "bi-stickies-fill",
        "content" => "bi-journals",
        "link" => "bi-link-45deg",
        "null" => "bi-question-diamond-fill",

        // Documents
        "word" => "bi-file-earmark-word-fill",
        "excel" => "bi-table",
        "pdf" => "bi-filetype-pdf",
        "powerpoint" => "bi-file-earmark-ppt-fill",
        "text" => "bi-file-earmark-text",

        // Images
        "image" => "bi-file-earmark-image",
        "jpeg" => "bi-filetype-jpg",
        "jpg" => "bi-filetype-jpg",
        "png" => "bi-filetype-png",
        "gif" => "bi-filetype-gif",
        "svg" => "bi-filetype-svg",

        // Audio
        "audio" => "bi-file-earmark-music",
        "mp3" => "bi-filetype-mp3",
        "wav" => "bi-filetype-wav",

        // Video
        "video" => "bi-file-earmark-play",
        "mp4" => "bi-filetype-mp4",
        "avi" => "bi-filetype-avi",
        "mov" => "bi-filetype-mov",

        // Archives
        "zip" => "bi-file-earmark-zip",
        "rar" => "bi-file-earmark-zip",
        "7z" => "bi-file-earmark-zip",

        // Code
        "code" => "bi-file-earmark-code",
        "html" => "bi-filetype-html",
        "css" => "bi-filetype-css",
        "js" => "bi-filetype-js",
        "json" => "bi-filetype-json",
        "php" => "bi-filetype-php",

        // Visibility
        "eye-shown" => "bi-eye-fill",
        "eye-hidden" => "bi-eye-slash-fill",

        // MIME Types
        "application/pdf" => "bi-filetype-pdf text-danger",
        "application/msword" => "bi-file-earmark-word-fill text-primary",
        "application/vnd.openxmlformats-officedocument.word" => "bi-file-earmark-word-fill text-primary",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "bi-file-earmark-word-fill text-primary",
        "application/vnd.ms-excel" => "bi-table text-success",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "bi-table text-success",
        "application/vnd.ms-powerpoint" => "bi-file-earmark-ppt-fill text-danger",
        "application/vnd.openxmlformats-officedocument.presentationml.presentation" => "bi-file-earmark-ppt-fill text-critical",
        "text/plain" => "bi-file-earmark-text",
        "image/jpeg" => "bi-file-earmark-image text-warning",
        "image/png" => "bi-file-earmark-image text-warning",
        "image/gif" => "bi-file-earmark-image text-warning",
        "audio/mpeg" => "bi-file-earmark-music",
        "audio/wav" => "bi-file-earmark-music",
        "video/mp4" => "bi-file-earmark-play",
        "video/x-ms-wmv" => "bi-file-earmark-play",
        "application/zip" => "bi-file-earmark-zip",
        "application/x-zip-compressed" => "bi-file-earmark-zip text-critical",
        "application/x-rar-compressed" => "bi-file-earmark-zip text-critical",
        "application/json" => "bi-file-earmark-code"
    ];

    return $icon[$icon_name] ?? "bi-question-diamond-fill";
}

function userHasPerms($perms = [])
{
    if ($perms) {
        return in_array($_SESSION['role'], $perms);
    } else {
        return false;
    }
}


function convertImageBlobToSrc($image_blob_data)
{
    return 'data:image/jpeg;base64,' . base64_encode($image_blob_data);
}

function convertProperDate($date, $date_filter)
{
    // Create a DateTime object
    $datetime = new DateTime($date);

    // Format the date to match the desired format
    $formatted_date = $datetime->format($date_filter);

    return $formatted_date;
}

function redirectViaJS($link)
{
    return "<script>window.location.href = '$link';</script>";
}

function timeElapsed($datetime)
{
    $timezone = new DateTimeZone('Asia/Manila'); // Set the timezone to Asia/Manila
    $now = new DateTime('now', $timezone); // Current time in Asia/Manila
    $givenTime = new DateTime($datetime, $timezone); // Given time in Asia/Manila
    $interval = $now->diff($givenTime); // Difference between the two times

    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return $interval->s . ' second' . ($interval->s > 1 ? 's' : '') . ' ago';
    }
}
