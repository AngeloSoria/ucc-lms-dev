<?php
require_once(FILE_PATHS['Functions']['PHPLogger']);

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
