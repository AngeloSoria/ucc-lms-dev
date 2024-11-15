<?php
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
$userController = new UserController($db);

// Get the role counts
$roleCountsResponse = $userController->getRoleCounts();

// Check if the response was successful
if ($roleCountsResponse['success'] === true) {
    // Convert counts to an associative array for easy access
    $roleCountsArray = [];  // Avoid overwriting previous variable
    foreach ($roleCountsResponse['data'] as $roleCount) {
        $roleCountsArray[$roleCount['role']] = $roleCount['role_count'];  // Use the correct index for count
    }
} else {
    // If the request failed, display an error message
    $errorMessage = $roleCountsResponse['error'];
    $roleCountsArray = [];  // Optional: Clear the array in case of error
}
?>

<div class="container-fluid bg-light shadow-sm rounded p-4 border">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-4 fw-semibold text-success m-0">User Overview</p>
    </div>
    <hr class="opacity-90 mx-0 my-2">

    <div class="d-flex container-fluid flex-wrap gap-3 mt-3">
        <?php if (!empty($roleCountsArray)): ?>
            <?php foreach ($roleCountsArray as $role => $count): ?>
                <div
                    class="bg-success rounded px-5 py-4 lh-1 text-light shadow-sm d-flex flex-column justify-content-center align-items-center gap-1 fw-semibold">
                    <p class="subtitle fs-2"><?php echo $count; ?></p>
                    <p class="title"><?php echo strtoupper($role); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-danger">
                <p>Error: <?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>