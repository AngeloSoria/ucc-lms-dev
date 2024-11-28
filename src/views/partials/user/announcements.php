<div class="widget-card p-3 shadow-sm rounded border overflow-hidden" id="myTasks" style="max-height: 230px;">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0">Announcements</p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
            <a href="javascript:void(0);" class="fs-6 d-flex justify-content-center align-items-center" title="Make Announcement">
                <i class="bi bi-plus-circle fs-6"></i>
            </a>
        <?php endif; ?>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <ul class="list-group list-group-flush bg-transparent gap-2 overflow-y-auto" style="max-height: 160px;">
            <?php
            $testAnnouncementCount = 1;
            ?>
            <?php if ($testAnnouncementCount > 0): ?>
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <li class="list-group-item d-flex justify-content-start align-items-center gap-2 fw-semibold p-0">
                        <i class="bi bi-megaphone-fill ctxt-secondary"></i>
                        <p class="title bg-transparent text-truncate fs-7">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Distinctio.
                        </p>
                    </li>
                <?php endfor; ?>
            <?php else: ?>
                <p class="fs-7 opacity-50 text-center p-2">No Announcements yet</p>
            <?php endif; ?>
        </ul>
    </div>
</div>