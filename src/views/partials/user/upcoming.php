<div class="widget-card p-3 shadow-sm rounded border" id="myTasks">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-5 fw-semibold text-success m-0">Upcoming</p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
            <a href="javascript:void(0);" class="fs-6 d-flex justify-content-center align-items-center" title="Make Announcement">
                <i class="bi bi-plus-circle fs-5"></i>
            </a>
        <?php endif; ?>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <!-- Your task list goes here -->
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-start align-items-center gap-2 fw-semibold">
                <i class="bi bi-circle-fill fs-6 text-success"></i>
                Task 1
            </li>
        </ul>
    </div>
</div>