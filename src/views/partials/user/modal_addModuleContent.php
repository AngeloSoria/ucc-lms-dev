<div class="modal fade" id="addModuleContentModal" tabindex="-1"
    aria-labelledby="addModuleContentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModuleContentLabel">Add Module
                    Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="moduleContentForm" method="POST"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="addModuleContent">
                    <!-- Content Title and Content Type (Same Row) -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="contentType" class="form-label">Content Type</label>
                            <select class="form-select" id="contentType"
                                name="input_contentType" required>
                                <option value="information">Information</option>
                                <option value="handout">Handout</option>
                                <option value="assignment">Assignment</option>
                                <option value="quiz">Quiz</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="contentTitle" class="form-label">Content Title</label>
                            <input type="text" class="form-control" id="contentTitle" name="input_contentTitle" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3" id="descriptionContainer">
                        <label for="description"
                            class="form-label">Description</label>
                        <textarea class="tinyMCE" id="description"
                            name="input_contentDescription"
                            placeholder="Enter description"></textarea>
                    </div>

                    <!-- File Input for Handout, Information, Assignment -->
                    <div class="mb-3" id="fileInputContainer">
                        <!-- <div class="form-check form-switch">
                            <label class="form-check-label" for="toggleFileInput">Upload Files</label>
                            <input class="form-check-input" type="checkbox" role="switch" id="toggleFileInput">
                        </div> -->
                        <label for="fileInput">Upload Files</label>
                        <input disabled type="file" class="mt-2 form-control" id="fileInput" name="input_contentFiles[]" multiple>
                    </div>

                    <!-- Assignment, Quiz -->
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Max Attempts -->
                            <div class="mb-3" id="maxAttemptsContainer">
                                <label for="maxAttempts" class="form-label">Max
                                    Attempts</label>
                                <input type="number" class="form-control"
                                    id="maxAttempts" name="input_contentMaxAttempts"
                                    min="1" placeholder="Enter max attempts"
                                    value="1">
                                <div class="invalid-feedback"></div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox"
                                        id="unlimitedAttempts"
                                        name="input_unlimitedAttempts">
                                    <label class="form-check-label"
                                        for="unlimitedAttempts">Unlimited</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Assignment Type -->
                            <div class="mb-3" id="assignmentTypeContainer">
                                <label for="assignmentType"
                                    class="form-label">Assignment Type</label>
                                <select class="form-select" id="assignmentType"
                                    name="input_contentAssignmentType">
                                    <option value="dropbox">Dropbox</option>
                                    <option value="richText">Rich Text</option>
                                    <option value="both">Both</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Max Score -->
                            <div class="mb-3" id="maxScoreContainer">
                                <label for="maxScoreContainer"
                                    class="form-label">Max Score</label>
                                <input class="form-control" type="number"
                                    name="input_contentMaxScore"
                                    id="maxScoreContainer" min="1" value="100"
                                    placeholder="either 100">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Date and Due Date (Same Row) -->
                    <div class="row g-3 mb-3" id="dateContainer">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start
                                Date</label>
                            <input type="datetime-local" class="form-control"
                                id="startDate" name="input_contentStartDate">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="dueDate" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control"
                                id="dueDate" name="input_contentDueDate">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Allow Late -->
                    <div class="form-check form-switch mb-3"
                        id="allowLateContainer">
                        <input class="form-check-input" type="checkbox" id="allowLate" name="input_contentAllowLate">
                        <label class="form-check-label" for="allowLate">Allow Late</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Content Visibility -->
                    <div class="form-check form-switch mb-3"
                        id="visibilityContainer">
                        <input class="form-check-input" type="checkbox"
                            id="visibility" name="input_contentVisibility">
                        <label class="form-check-label"
                            for="visibility">Visibility</label>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <span type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</span>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>