<?php
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
$userController = new UserController();

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

<div class="bg-light shadow-sm rounded p-4 border">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-4 fw-semibold text-success m-0">User Overview</p>
    </div>
    <hr class="opacity-90 mx-0 my-2">

    <div class="row">
        <?php if (!empty($roleCountsArray)): ?>
            <?php foreach ($roleCountsArray as $role => $count): ?>
                <div class="p-1 col-md-12 col-lg-6 col-xxl-3">
                    <div class="rounded p-2 flex-grow-1 position-relative" style="background: var(--c-brand-primary-linear-gradient);">
                        <p class="fs-2 text-white fw-bolder"><?php echo $count; ?></p>
                        <p class="bg-transparent text-white"><?php echo ($count > 1) ? strtoupper($role) . 'S' : strtoupper($role); ?></p>

                        <div class="dropdown position-absolute top-0 end-0">
                            <button class="btn btn-lg text-white" title="More" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" type="button" onclick="window.location = 'users_admin.php?viewRole=<?php echo $role ?>';">View</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-danger">
                <p>Error: <?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>