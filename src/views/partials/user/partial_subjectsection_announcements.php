<?php

$getAnnouncementsBySubjectSection = $announcementController->getAnnouncements($_GET['subject_section_id']);

?>

<section class="px-2 mb-4">
    <div>
        <p class="fs-5">Announcements</p>
    </div>
    <section id="announcement-container" class="container w-90">
        <div id="announcement-controls" class="d-flex justify-content-end align-items-center">
            <div class="btn-group mt-2" role="group" aria-label="Basic example">
                <?php if (userHasPerms(['Teacher'])): ?>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#announcementFormModal">
                        <i class="bi bi-plus-lg"></i>
                        Post
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div id="announcement-content" class="mt-2 d-flex flex-column gap-3 justify-content-start align-items-center">
            <?php if ($getAnnouncementsBySubjectSection['success']): ?>
                <?php foreach ($getAnnouncementsBySubjectSection['data'] as $announcement): ?>
                    <div id="announcement_<?php echo $announcement['id'] ?>" class="border border-1 border-success rounded container-fluid px-0 py-3 bg-white shadow-sm">
                        <div class="row px-4">
                            <div class="col d-flex justify-content-start align-items-center gap-2">
                                <i class="bi bi-megaphone-fill fs-3 ctxt-secondary"></i>
                                <a href="<?php echo BASE_PATH_LINK . 'src/views/users/viewProfile.php?viewProfile=' . $announcement['announcer_id'] ?>" title="Click to view profile">
                                    <?php echo sanitizeInput($announcement['announcer_name']) ?>
                                </a>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center gap-3">
                                <p>
                                    <?php echo convertProperDate($announcement['created_at'], "M d, h:i a") ?>
                                </p>
                                <?php if (userHasPerms(['Teacher'])): ?>
                                    <div>
                                        <form method="POST" onsubmit="deleteAnnouncement(event, this);">
                                            <input type="hidden" name="action" value="deleteAnnouncement_subjectsection">
                                            <input type="hidden" name="announcement_id" value="<?php echo $announcement['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="px-4">
                            <h5 id="announcement-title" class="mb-3">
                                <?php echo sanitizeInput($announcement['title']) ?>
                            </h5>
                            <p id="announcement-desc">
                                <?php echo $announcement['message'] ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <script>
                    function deleteAnnouncement(e, o) {
                        e.preventDefault();
                        if (confirm("Do you want to delete this announcement?")) {
                            o.submit();
                        }
                    }
                </script>
            <?php else: ?>
                <p class="fs-6 text-muted"><?php echo sanitizeInput($getAnnouncementsBySubjectSection['message']); ?></p>
            <?php endif; ?>
        </div>
    </section>
</section>
<?php if (userHasPerms(['Teacher'])): ?>
    <div class="modal fade" id="announcementFormModal" tabindex="-1" aria-labelledby="announcementFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="announcementFormModalLabel">Announcement Form (Subject Section)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="addAnnouncement_subjectsection">
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