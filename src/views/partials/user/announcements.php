<div class="widget-card p-3 shadow-sm rounded border overflow-hidden" id="myTasks" style="max-height: 230px;">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-6 fw-semibold text-success m-0">Announcements</p>
        <?php if (userHasPerms(['Admin'])): ?>
            <a href="javascript:void(0);" class="fs-6 d-flex justify-content-center align-items-center" title="Make Announcement" role="button" data-bs-toggle="modal" data-bs-target="#announcementFormModal">
                <i class="bi bi-plus-circle fs-6"></i>
            </a>
        <?php endif; ?>
    </div>
    <hr class="opacity-90 mx-0 my-2">
    <div>
        <ul class="list-group list-group-flush bg-transparent gap-2 overflow-y-auto" style="max-height: 160px;">
            <?php
            $announcements = [];
            if (isset($_GET['subject_section_id'])) {
                $announcements = $announcementController->getAnnouncements($_GET['subject_section_id']);
            } else {
                $announcements = $announcementController->getAnnouncements();
            }
            ?>
            <?php if ($announcements['success']): ?>
                <?php foreach ($announcements['data'] as $announcement): ?>
                    <li class="list-group-item d-flex justify-content-start align-items-center gap-2 fw-semibold p-0">
                        <i class="bi bi-megaphone-fill ctxt-secondary"></i>
                        <p class="title bg-transparent text-truncate fs-7">
                            <?php echo sanitizeInput($announcement['title']) ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="fs-7 opacity-50 text-center p-2"><?php echo sanitizeInput($announcements['message']); ?></p>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php if (userHasPerms(['Admin'])): ?>
    <div class="modal fade" id="announcementFormModal" tabindex="-1" aria-labelledby="announcementFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="announcementFormModalLabel">Announcement Form (Global)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="addAnnouncement_global">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="#input_announcementTitle">Title</label>
                            <input required type="text" name="input_announcementTitle" id="input_announcementTitle" placeholder="Enter announcement title" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="#input_announcementMessage">Description</label>
                            <textarea name="input_announcementMessage" id="input_announcementMessage" class="tinyMCE" placeholder="Enter announcement description."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>