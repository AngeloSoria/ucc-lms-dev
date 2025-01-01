<div class="mt-sm-2 mt-md-0 widget-card p-3 shadow-sm rounded border" id="myTasks">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0"><?php echo sanitizeInput(ucfirst($module_contentInfo['data'][0]['content_type'])) ?></p>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <!-- Your task list goes here -->
        <ul class="list-group list-group-flush">

            <li class="list-group-item px-0 d-flex justify-content-start align-items-center gap-2">
                <i class="bi bi-archive-fill text-critical fs-7"></i>
                <span class="fs-7">
                    <?php
                    $assignmentType = $module_contentInfo['data'][0]['assignment_type'] == 'both' ? "Text & Dropbox" : $module_contentInfo['data'][0]['assignment_type'];
                    ?>
                    Type: <?php echo sanitizeInput(ucfirst($assignmentType)) ?>
                </span>
            </li>
            <li class="list-group-item px-0 d-flex justify-content-start align-items-center gap-2">
                <i class="bi bi-bar-chart-line-fill text-primary fs-7"></i>
                <span class="fs-7">
                    Max score: <?php echo sanitizeInput(ucfirst($module_contentInfo['data'][0]['max_score'])) ?>
                </span>
            </li>
            <li class="list-group-item px-0 d-flex justify-content-start align-items-center gap-2">
                <i class="bi bi-calendar-week text-warning fs-7"></i>
                <span class="fs-7">
                    <?php
                    $dateTime = new DateTime($module_contentInfo['data'][0]['start_date']);
                    $formatDate = $dateTime->format('M d, g:i a');
                    ?>
                    Start: <?php echo sanitizeInput(ucfirst($formatDate)) ?>
                </span>
            </li>
            <li class="list-group-item px-0 d-flex justify-content-start align-items-center gap-2">
                <i class="bi bi-calendar-week text-warning fs-7"></i>
                <span class="fs-7">
                    <?php
                    $dateTime = new DateTime($module_contentInfo['data'][0]['due_date']);
                    $formatDate = $dateTime->format('M d, g:i a');
                    ?>
                    Start: <?php echo sanitizeInput(ucfirst($formatDate)) ?>
                </span>
            </li>

        </ul>
    </div>
</div>