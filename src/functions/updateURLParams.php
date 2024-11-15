<?php
// Example usage:
// echo updateUrlParams(['view' => 'admin']); // Adds ?view=admin to the current URL
// echo updateUrlParams(['view' => 'admin', 'id' => 1001]); // Adds file.php?view=admin&id=1001 to the current URL
function updateUrlParams($params = [])
{
    // Get the current URL path without query parameters
    $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');

    // Merge existing query parameters with the new ones
    $queryParams = array_merge($_GET, $params);

    // Build the new query string, ensuring proper parameter separation
    $queryString = http_build_query($queryParams);

    // Return the updated URL with the new query string
    return $currentUrl . ($queryString ? '?' . $queryString : '');
}


function clearUrlParams()
{
    // Get the current URL path without query parameters
    return strtok($_SERVER["REQUEST_URI"], '?');
}
