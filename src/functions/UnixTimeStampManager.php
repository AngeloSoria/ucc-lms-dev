<?php
// Get the current Unix timestamp
function getCurrentUnixTimestamp()
{
    return time();
}

// Add minutes to the current Unix timestamp
function getCurrentUnixTimestamp_addByMinutes($minutes = 1)
{
    return getCurrentUnixTimestamp() + ($minutes * 60);
}

// Add hours to the current Unix timestamp
function getCurrentUnixTimestamp_addByHours($hours = 1)
{
    return getCurrentUnixTimestamp() + ($hours * 3600);
}

// Add days to the current Unix timestamp
function getCurrentUnixTimestamp_addByDays($days = 1)
{
    return getCurrentUnixTimestamp() + ($days * 86400);
}
