<div class="modal fade" id="uploadSubmissionModal" tabindex="-1" data-bs-focus="false" aria-labelledby="uploadSubmissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadSubmissionModalLabel">Submission Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="addContentSubmission">
                <div class="modal-body">
                    <?php
                    $showTextArea = in_array($module_contentInfo['data'][0]['assignment_type'], ['both', 'rich_text']);
                    $showFileInput = in_array($module_contentInfo['data'][0]['assignment_type'], ['both', 'dropbox']);
                    $isRequired = $module_contentInfo['data'][0]['assignment_type'] != 'both' ? 'required' : '';
                    ?>

                    <?php if ($showTextArea): ?>
                        <section class="row">
                            <div class="col-md-12">
                                <p>Text Answer</p>
                                <textarea id="submission_Text" name="submission_Text" class="tinyMCE" placeholder="Enter your text answer here..."></textarea>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if ($showFileInput): ?>
                        <section class="row mt-2">
                            <div class="col-md-12">
                                <p>File Answer</p>
                                <input id="submission_Files" type="file" class="mt-2 form-control" name="submission_Files[]" multiple>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>

                <script>
                    $(document).ready(function() {
                        const assignmentType = "<?php echo $module_contentInfo['data'][0]['assignment_type']; ?>";

                        $('form').on('submit', function(event) {
                            if (assignmentType === 'both') {
                                const textAreaValue = $('#submission_Text').val().trim();
                                const fileInputValue = $('#submission_Files')[0].files.length > 0;

                                if (!textAreaValue && !fileInputValue) {
                                    event.preventDefault();
                                    alert('Please provide data in either the text area or the file input.');
                                }
                            }
                        });
                    });
                </script>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>