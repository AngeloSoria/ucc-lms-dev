<?php

function initCard() {
    
}

?>

<div id="data_view_catalog" class="d-flex justify-content-start align-items-start gap-2 flex-wrap">
    <?php
    if (!empty($programList)) {
        foreach ($programList as $program) {
            // Ensure the image is available before converting
            $base64Image = !empty($program['program_image']) ? base64_encode($program['program_image']) : '';
    ?>
            <div class="c-card card cbg-primary text-white border-0 shadow-sm">
                <div class="card-preview position-relative w-100 bg-success d-flex overflow-hidden justify-content-center align-items-center" style="min-height: 200px; max-height: 200px;">
                    <?php if ($base64Image): ?>
                        <img src="data:image/jpeg;base64,<?php echo $base64Image; ?>" class="rounded card-img-top img-programs position-absolute top-50 start-50 translate-middle object-fit-fill" alt="<?php echo htmlspecialchars($program['program_name']); ?>">
                    <?php else: ?>
                        <div class="text-center text-muted">No image available</div>
                    <?php endif; ?>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-10">
                            <h6 class="card-title w-100 fw-bold bg-transparent" style="height: 4rem;"><?php echo htmlspecialchars($program['program_name']); ?></h6>
                            <p class="card-text fs-6"><?php echo htmlspecialchars($program['program_description']); ?></p>
                            <p class="card-text fs-6">Level: <?php echo htmlspecialchars($program['educational_level']); ?></p>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end align-items-start">
                            <div class="dropdown">
                                <button class="btn btn-lg c-primary p-0 text-white dropdown-toggle dropdown-no-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#" onclick="">Configure</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<p class="text-danger">No programs available.</p>';
    }
    ?>
</div>