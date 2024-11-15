<?php
// Example usage:
// echo updateUrlParams(['view' => 'admin']); // Adds ?view=admin to the current URL
function updateUrlParams($params = [])
{
    // Get the current URL path without query parameters
    $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');

    // Merge existing query parameters with the new ones
    $queryParams = array_merge($_GET, $params);

    // Build the new URL with the updated query string
    return $currentUrl . '?' . http_build_query($queryParams);
}

function clearUrlParams()
{
    // Get the current URL path without query parameters
    return strtok($_SERVER["REQUEST_URI"], '?');
}
