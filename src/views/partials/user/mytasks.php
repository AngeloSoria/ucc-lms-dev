<div class="mt-sm-2 mt-md-0 widget-card p-3 shadow-sm rounded border" id="myTasks">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0">My Tasks</p>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <!-- Your task list goes here -->
        <ul class="list-group list-group-flush overflow-y-auto" style="max-height: 150px;">
            <?php for ($i = 0; $i < 20; $i++) { ?>
                <li class="list-group-item px-0 d-flex justify-content-start align-items-center gap-2">
                    <i class="bi bi-circle-fill fs-8 text-success"></i>
                    <span class="fs-7">Subject Name (99)</span>
                </li>
            <?php } ?>
        </ul>
        <a href="#" class="text-decoration-none text-dark d-block w-100 text-center fs-7 opacity-50 mt-2">View All Tasks</a>
    </div>
</div>