<?php
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
$userController = new UserController($db);

// Get the role counts
$roleCounts = $userController->getRoleCounts();

// Convert counts to an associative array for easy access
$roleCountsArray = [];  // Use a different name to avoid overwriting
foreach ($roleCounts as $roleCount) {
    $roleCountsArray[$roleCount['role']] = $roleCount['role_count'];  // Use the correct index for count
}
?>

<div class="container-fluid bg-light shadow-sm rounded p-4 border">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-4 fw-semibold text-success m-0">User Overview</p>
    </div>
    <hr class="opacity-90 mx-0 my-2">

    <div class="d-flex container-fluid flex-wrap gap-3 mt-3">
        <?php foreach ($roleCountsArray as $role => $count): ?>
            <div
                class="bg-success rounded px-5 py-4 lh-1 text-light shadow-sm d-flex flex-column justify-content-center align-items-center gap-1 fw-semibold">
                <p class="subtitle fs-2"><?php echo $count; ?></p>
                <p class="title"><?php echo strtoupper($role); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>