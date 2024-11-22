<?php
// Example usage:
// echo updateUrlParams(['view' => 'admin']); // Adds ?view=admin to the current URL
// echo updateUrlParams(['view' => 'admin', 'id' => 1001]); // Adds file.php?view=admin&id=1001 to the current URL
function updateUrlParams($params = [])
{
    // Current URL path (e.g., test.php)
    $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');

    // Build the query string from $params
    $queryString = http_build_query($params);

    // Return the updated URL
    return $currentUrl . ($queryString ? '?' . $queryString : '');
}

function clearUrlParams()
{
    // Get the current URL path without query parameters
    return strtok($_SERVER["REQUEST_URI"], '?');
}
