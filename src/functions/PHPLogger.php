<?php

function msgLog($logLevel = "INFO", $message = "no message given")
{
    if (isset($_ENV['SAVE_LOCAL_LOGS']) && $_ENV['SAVE_LOCAL_LOGS'] == 'true') {
        // Define the log file path (update as needed)
        $logFile = FILE_PATHS['LOGS'];

        // Get the current timestamp
        $timestamp = date("Y-m-d H:i:s");

        // Format the log message
        $formattedMessage = sprintf("[%s] [%s] %s\n", $timestamp, $logLevel, $message);

        // Append the formatted message to the log file
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}
