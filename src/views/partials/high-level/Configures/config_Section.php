<div class="mb-3 row align-items-start">
    <hr>
    <ul class="nav nav-tabs border-bottom px-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab" aria-controls="information" aria-selected="true">Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">Subjects</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">Students</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active pt-2" id="information" role="tabpanel" aria-labelledby="information-tab">
            <form method="POST">
                <input type="hidden" name="action" value="updateSectionInfo">
                <div class="position-relative p-3">
                    <div class="card-body">
                        <div class="row mb-4">
                            <h5 class="display-7 text-start">Section Information</h5>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <h6 class="d-flex gap-1">Section Name<code class="fs-6">*</code></h6>
                                <input name="input_sectionName" class="form-control" type="text" value="<?= htmlspecialchars($retrievedSection['data']['section_name']) ?>">
                            </div>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <h6 class="text-truncate">Educational Level</h6>
                                <select id="input_sectionEducationalLevel" class="form-select" disabled>
                                    <?php
                                    $option1 = htmlspecialchars($enrolledProgramToSection['data'][0]['educational_level']);
                                    $option2 = $option1 == "College" ? "SHS" : "College";
                                    ?>
                                    <option value="<?php echo $option1 ?>" selected><?php echo $option1 ?></option>
                                    <option value="<?php echo $option2 ?>"><?php echo $option2 ?>
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-5 mb-3">
                                <h6 class="d-flex gap-1">Program<code class="fs-6">*</code></h6>
                                <select name="input_sectionProgram" id="input_sectionPrograms" class="form-select" title="Enrolled Program">
                                    <?php if ($retrievedSection['success'] && !empty($enrolledProgramToSection['data'][0]) && $retrievedAllPrograms['success']): ?>
                                        <?php foreach ($retrievedAllPrograms['data'] as $programs): ?>
                                            <?php if ($programs['educational_level'] === $enrolledProgramToSection['data'][0]['educational_level']): ?>
                                                <option value="<?php echo $programs['program_id']; ?>" <?php echo ($enrolledProgramToSection['data'][0]['program_id'] === $programs['program_id']) ? "selected" : ""; ?>>
                                                    <?php echo htmlspecialchars($programs['program_code'] . " | " . $programs['program_name']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option>Nothing selected</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                                <h6>Semester</h6>
                                <select class="form-select" id="" disabled>
                                    <?php if ($retrievedSection['success']): ?>
                                        <option
                                            value="<?= htmlspecialchars($retrievedSection['data']['semester']) ?>">
                                            <?= htmlspecialchars($retrievedSection['data']['semester']) ?>
                                        </option>
                                        <?php if ($retrievedSection['data']['semester'] == 1): ?>
                                            <option value="2">2</option>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <h6 class="d-flex gap-1">Year Level<code class="fs-6">*</code></h6>
                                <select id="input_sectionYearLevel" name="input_sectionYearLevel" class="form-select">
                                    <?php
                                    // Determine the educational level
                                    $educational_level = htmlspecialchars($enrolledProgramToSection['data'][0]['educational_level']);
                                    $selected_year_level = $retrievedSection['data']['year_level']; // Get the selected year level

                                    // Populate year level options based on the educational level
                                    if ($educational_level === 'SHS') {
                                        echo '<option value="11"' . ($selected_year_level == 11 ? ' selected' : '') . '>11</option>';
                                        echo '<option value="12"' . ($selected_year_level == 12 ? ' selected' : '') . '>12</option>';
                                    } elseif ($educational_level === 'College') {
                                        echo '<option value="1"' . ($selected_year_level == 1 ? ' selected' : '') . '>1</option>';
                                        echo '<option value="2"' . ($selected_year_level == 2 ? ' selected' : '') . '>2</option>';
                                        echo '<option value="3"' . ($selected_year_level == 3 ? ' selected' : '') . '>3</option>';
                                        echo '<option value="4"' . ($selected_year_level == 4 ? ' selected' : '') . '>4</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-5">
                                <div class="col-12">
                                    <h6 class="d-flex gap-1">Section Adviser<code class="fs-6">*</code></h6>
                                    <select class="form-select" name="input_sectionTeacherAdviser" id="input_sectionTeacherAdviser">
                                        <?php if ($enrolledAdviserToSection['success']): ?>
                                            <option value="<?php echo $enrolledAdviserToSection['data']['user_id'] ?>">
                                                <?php echo $enrolledAdviserToSection['data']['first_name'] . ' ' . $enrolledAdviserToSection['data']['last_name'] ?>
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Check if enrolledAdviserToSection contains data and sanitize it
                        if ($enrolledAdviserToSection['success']) {
                            $sanitize = $enrolledAdviserToSection['data']['first_name'] . ' ' . $enrolledAdviserToSection['data']['last_name'] . ' (' . $enrolledAdviserToSection['data']['user_id'] . ')';
                            $defaultUserId = $enrolledAdviserToSection['data']['user_id']; // Extract user_id for the default value
                            $defaultTeacherName = htmlspecialchars($sanitize); // Sanitize the teacher name to use safely in JS
                        } else {
                            $defaultUserId = null;
                            $defaultTeacherName = null;
                        }
                        ?>

                        <script>
                            $(document).ready(function() {
                                // Initialize select2 with AJAX configuration
                                $("#input_sectionTeacherAdviser").select2({
                                    placeholder: "Search subject teacher to add", // Placeholder text
                                    allowClear: true, // Allow clearing the selection
                                    ajax: {
                                        url: "", // Empty URL to use the current URL
                                        type: "POST",
                                        dataType: "json",
                                        delay: 250,
                                        data: function(params) {
                                            return {
                                                search_type: "teacher",
                                                query: params.term, // Search query from user input
                                                additional_filters: {
                                                    educational_level: $("#input_sectionEducationalLevel").val(), // Filter by educational level
                                                },
                                            };
                                        },
                                        processResults: function(data) {
                                            return {
                                                results: data.map(function(teacher) {
                                                    return {
                                                        id: teacher.user_id,
                                                        text: `${teacher.name} (${teacher.user_id})`
                                                    };
                                                })
                                            };
                                        }
                                    }
                                });

                                // Check if the default values are set in PHP
                                <?php if ($defaultUserId !== null && $defaultTeacherName !== null): ?>
                                    // If a default adviser is provided, set it as the default selected value in select2
                                    const defaultOption = new Option("<?php echo $defaultTeacherName; ?>", "<?php echo $defaultUserId; ?>", true, true);
                                    $("#input_sectionTeacherAdviser").append(defaultOption).trigger('change');
                                <?php endif; ?>

                                // Log the value after setting
                                console.log($("#input_sectionTeacherAdviser").val());
                            });
                        </script>


                        <div class="row mb-3">
                            <div class="col-lg-3 p-3">
                                <div id="modalDisclaimer" class="card p-3 text-light fs-7 mt-sm-3 bg-danger">
                                    <div class="mb-1 border-bottom d-flex justify-content-between align-center">
                                        <span class="fs-6 fw-semibold">Disclaimer</span>
                                        <span class="fs-5" role="button" title="close" id="btnDisclaimer">
                                            <i class="bi bi-x"></i>
                                        </span>
                                        <script>
                                            $("#btnDisclaimer").on("click", function() {
                                                $("#modalDisclaimer").hide();
                                            });
                                        </script>
                                    </div>
                                    <ul class="px-2 d-flex flex-column gap-2">
                                        <li>Some of input fields are disabled as they are connected with other forms, such as enrollments of students and subjects.</li>
                                        <li>Enabling them may conflict the enrollment of students or subjects.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="float-end d-flex gap-2">
                        <button type="submit" class="btn btn-success d-flex gap-2" id="btnUpdate">
                            <i class="bi bi-floppy-fill"></i>
                            Update
                        </button>
                        <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete">
                            <i class="bi bi-trash-fill"></i>
                            Delete Subject
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <!-- STUDENTS -->
        <div class="tab-pane fade pt-2" id="students" role="tabpanel" aria-labelledby="students-tab">
            <form method="POST">
                <div class="col-md-12 p-3 rounded">
                    <section class="role_table">
                        <h5>Enrolled Students</h5>
                        <!-- =============================================== -->
                        <!-- DATA TABLE BY STUDENTS -->
                        <section class="row mb-3">
                            <div class="col d-flex align-items-center justify-content-end gap-2">
                                <span class="btn btn-success d-flex align-items-center justify-content-center gap-2" role="button" id="enrollStudents" data-bs-toggle="modal" data-bs-target="#enrollRegularStudentModal">
                                    <i class="bi bi-plus-circle"></i>
                                    Enroll Students
                                </span>
                                <span class="btn btn-sm btn-danger disabled">
                                    <i class="bi bi-trash"></i>
                                    Remove Selection
                                </span>
                            </div>
                        </section>

                        <table id="dataTable_enrolledStudents"
                            class="table table-responsive table-bordered table-hover table-sm border"
                            style="width: 100%">
                            <caption>Table of Enrolled Students</caption>
                            <thead class="table-brand-secondary">
                                <tr>
                                    <th class="text-center" style="max-width: 4%;">
                                        <input type="checkbox" name="" id="" class="form-check-input">
                                    </th>
                                    <th class="text-center" style="max-width: 5%;">User Id</th>
                                    <th>Student Name</th>
                                    <th class="text-center" style="max-width: 10%;">Enrollment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($enrolledStudentInfoFromSection)) { ?>
                                    <tr>
                                        <td class="text-center" colspan="5">No Enrolled Students</td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($enrolledStudentInfoFromSection as $userData) { ?>
                                        <tr class="table-default">
                                            <td class="text-center">
                                                <input type="checkbox" name="" id="" class="form-check-input">
                                            </td>
                                            <td class="align-center text-center">
                                                <a title="View User" class="badge badge-secondary" href="users_admin.php<?php echo htmlspecialchars('?viewRole=' . $userData['role'] . '&user_id=' . $userData['user_id']) ?>">
                                                    <?php echo $userData['user_id'] ?>
                                                </a>
                                            </td>
                                            <td><?php echo $userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name'] ?></td>
                                            <td class="text-center" style="width: 10%;">
                                                <span class="badge <?php echo $userData['enrollment_type'] == "regular" ? "badge-primary" : "badge-danger" ?>">
                                                    <?php echo ucfirst($userData['enrollment_type']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:alert('work in progress')" title="Remove"
                                                    class="btn btn-sm btn-danger m-auto disabled">
                                                    <i class="bi bi-person-fill-add"></i>
                                                    Remove
                                                </a>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function() {
                                // Initialize DataTable
                                $('#dataTable_enrolledStudents').DataTable({
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    order: [],
                                    columnDefs: [{
                                        targets: [0, -1], // Disable ordering for the first and last columns
                                        orderable: false
                                    }]
                                });

                                // Select All functionality
                                $('#checkbox_selectAll').on('change', function() {
                                    const isChecked = $(this).is(':checked');
                                    $('#dataTable_allUsers tbody input[type="checkbox"]').prop('checked', isChecked);
                                });

                                // Ensure "Select All" reflects individual checkbox changes
                                $('#dataTable_allUsers tbody').on('change', 'input[type="checkbox"]', function() {
                                    const totalCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]').length;
                                    const checkedCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]:checked').length;

                                    $('#checkbox_selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                                });

                            });
                        </script>

                        <!-- END OF DATA TABLE -->
                        <!-- =============================================== -->
                    </section>

                </div>
            </form>
        </div>
        <!-- SUBJECTS -->
        <div class="tab-pane fade pt-2" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
            <form method="POST">
                <div class="col-md-12 p-2 rounded">
                    <section class="role_table">
                        <h5>Subject Enrollment</h5>
                        <!-- =============================================== -->
                        <!-- DATA TABLE BY SUBJECTS -->
                        <section class="row mb-3">
                            <div class="col d-flex align-items-center justify-content-end gap-2">
                                <span class="btn btn-success d-flex align-items-center justify-content-center gap-2" role="button" id="enrollSubject" data-bs-toggle="modal" data-bs-target="#enrollSubjectForm">
                                    <i class="bi bi-plus-circle"></i>
                                    Enroll Subject
                                </span>
                                <span class="btn btn-sm btn-danger disabled">
                                    <i class="bi bi-trash"></i>
                                    Remove Selection
                                </span>
                            </div>
                        </section>


                        <table id="dataTable_enrolledSubjects"
                            class="table table-responsive table-hover border table-bordered"
                            style="width: 100%">
                            <caption>Table of Enrolled Subjects</caption>
                            <thead class="table-brand-secondary">
                                <tr>
                                    <th class="text-center" style="max-width: 1%;"><input type="checkbox" id="checkbox_selectAll" class="form-check-input" accesskey="" value="<?php echo $_GET['viewSection'] ?>"></th>
                                    <th class="text-center" style="max-width: 5%;">Subject Id</th>
                                    <th>Subject Name</th>
                                    <th>Instructor</th>
                                    <th class="text-center">No. Enrolled Students</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($enrolledSubjectsFromSection['data'])) { ?>
                                    <tr>
                                        <td class="text-center" colspan="6">No Enrolled Subjects</td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                        <td class="text-center d-none"></td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($enrolledSubjectsFromSection['data'] as $subjectSectionData) {
                                        $subjectInfo = $subjectController->getSubjectFromSubjectId($subjectSectionData['subject_id']);
                                        $totalEnrolledStudentsInSubject = $subjectSectionController->getNumberOfEnrolledStudentsInSubject($subjectSectionData['subject_section_id']);
                                        if ($subjectInfo['success']):
                                            $subjectInstructorInfo = $userController->getUserById($subjectSectionData['teacher_id']);
                                            $instructorName = $subjectInstructorInfo['success'] ? ($subjectInstructorInfo['data']['first_name'] . " " . $subjectInstructorInfo['data']['last_name']) : "N/A";
                                    ?>
                                            <tr class="table-default">
                                                <td class="text-center text-truncate"><input type="checkbox" title="selectAll" class="form-check-input" value="<?php htmlspecialchars($subjectData['subject_id'] ?? '') ?>"></td>
                                                <td class="align-center text-center">
                                                    <a title="View Subject" class="badge badge-primary" href="<?php echo "subjects_admin.php?viewSubject=" . $subjectSectionData['subject_id'] ?>">
                                                        <?php echo $subjectInfo['data']['subject_id'] ?>
                                                    </a>
                                                </td>
                                                <td class="" contenteditable="true"><?php echo $subjectInfo['data']['subject_name'] ?></td>
                                                <td class=""><?php echo $instructorName ?></td>
                                                <td class="text-center"><?php echo $totalEnrolledStudentsInSubject ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo updateUrlParams(['viewSection' => $_GET['viewSection'], 'subject_section_id' => $subjectSectionData['subject_section_id']]) ?>" title="enroll"
                                                        class="btn btn-sm btn-primary m-auto">
                                                        <i class="bi bi-person-fill-add"></i>
                                                        Enroll
                                                    </a>
                                                    <a href="javascript:void(0)" title="remove" target_subject_section_id="<?php echo $subjectSectionData['subject_section_id'] ?>"
                                                        class="btn btn-sm btn-danger m-auto btnDelete_singular">
                                                        <i class="bi bi-trash-fill"></i>
                                                        Remove
                                                    </a>
                                                </td>
                                            </tr>
                                <?php endif;
                                    }
                                } ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function() {
                                // Delete function per item
                                $(".btnDelete_singular").on("click", function(e) {
                                    e.preventDefault(); // Prevent the default action of the button

                                    const target_subject_section_id = $(this).attr("target_subject_section_id");
                                    if (target_subject_section_id !== undefined) {
                                        // Insert the Bootstrap 5 confirmation modal into the DOM
                                        const confirmationModal = `
                                            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this item?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;

                                        // Append modal to the body
                                        $("body").append(confirmationModal);

                                        // Show the modal
                                        const modalInstance = new bootstrap.Modal($("#confirmationModal"));
                                        modalInstance.show();

                                        // Handle the confirmation button click
                                        $("#confirmDelete").on("click", function() {
                                            // Perform the AJAX call
                                            $.ajax({
                                                url: "",
                                                type: "POST",
                                                data: {
                                                    action: "deleteSubjectsFromSection",
                                                    subject_section_ids: [target_subject_section_id]
                                                },
                                                success: function(response) {
                                                    console.log("success");
                                                    modalInstance.hide();
                                                    $("#confirmationModal").remove(); // Remove modal from DOM after closing
                                                    console.log(response);
                                                    if (response.redirect) {
                                                        window.location.href = response.redirect; // Redirect the page
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error(error);
                                                    modalInstance.hide();
                                                    $("#confirmationModal").remove(); // Remove modal from DOM after closing
                                                }
                                            });
                                        });

                                        // Clean up modal from DOM when it's hidden
                                        $("#confirmationModal").on("hidden.bs.modal", function() {
                                            $(this).remove();
                                        });
                                    }
                                });


                                // Initialize DataTable
                                $('#dataTable_enrolledSubjects').DataTable({
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    order: [],
                                    columnDefs: [{
                                        targets: [0, -1], // Disable ordering for the first and last columns
                                        orderable: false
                                    }]
                                });

                                // Select All functionality
                                $('#checkbox_selectAll').on('change', function() {
                                    const isChecked = $(this).is(':checked');
                                    $('#dataTable_allUsers tbody input[type="checkbox"]').prop('checked', isChecked);
                                });

                                // Ensure "Select All" reflects individual checkbox changes
                                $('#dataTable_allUsers tbody').on('change', 'input[type="checkbox"]', function() {
                                    const totalCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]').length;
                                    const checkedCheckboxes = $('#dataTable_allUsers tbody input[type="checkbox"]:checked').length;

                                    $('#checkbox_selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                                });

                            });
                        </script>

                        <!-- END OF DATA TABLE -->
                        <!-- =============================================== -->
                    </section>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ENROLL SUBJECT MODAL -->
<div class="modal fade" id="enrollSubjectForm" tabindex="-1" aria-labelledby="enrollSubjectFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="enrollSubjectFormLabel">Enroll Subject <?php echo ' | ' . $enrolledProgramToSection['data'][0]['educational_level'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="enrollSubjectToSection" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="enrollSubjectInstructor">
                    <input type="hidden" name="multi_subject" value="false">
                    <input type="hidden" id="inp_educational_level" name="educational_level" value="<?php echo $enrolledProgramToSection['data'][0]['educational_level'] ?>">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-12 d-flex flex-column">
                            <label for="input_enrollSubject" class="form-label">Subject</label>
                            <select class="form-select" id="input_enrollSubject" name="input_enrollSubject"></select>
                        </div>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-12 d-flex flex-column">
                            <label for="input_enrollInstructor" class="form-label">Instructor</label>
                            <select class="form-select" id="input_enrollInstructor" name="input_enrollInstructor"></select>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Initialize select2 with AJAX configuration
                            $("#input_enrollSubject").select2({
                                dropdownParent: $('#enrollSubjectForm'),
                                width: "100%",
                                placeholder: "Search subjects to add", // Placeholder text
                                allowClear: true, // Allow clearing the selection
                                ajax: {
                                    url: "", // Empty URL to use the current URL
                                    type: "POST",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params) {
                                        console.log($("#inp_educational_level").val());

                                        return {
                                            search_type: "subject",
                                            query: params.term, // Search query from user input
                                            additional_filters: {
                                                educational_level: $("#inp_educational_level").val(), // Filter by educational level
                                            },
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data.map(function(subject) {
                                                return {
                                                    id: subject.subject_id,
                                                    text: `${subject.subject_code} | ${subject.name}`
                                                };
                                            })
                                        };
                                    }
                                }
                            });
                            $("#input_enrollInstructor").select2({
                                dropdownParent: $('#enrollSubjectForm'),
                                placeholder: "Search subject instructor to add", // Placeholder text
                                allowClear: true, // Allow clearing the selection
                                ajax: {
                                    url: "", // Empty URL to use the current URL
                                    type: "POST",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            search_type: "teacher",
                                            query: params.term, // Search query from user input
                                            additional_filters: {
                                                educational_level: $("#inp_educational_level").val(), // Filter by educational level
                                            },
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data.map(function(teacher) {
                                                return {
                                                    id: teacher.user_id,
                                                    text: `${teacher.name} (${teacher.user_id})`
                                                };
                                            })
                                        };
                                    }
                                }
                            });
                        });
                    </script>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary c-primary" form="enrollSubjectToSection">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ENROLL STUDENT MODAL -->
<div class="modal fade" id="enrollRegularStudentModal" tabindex="-1" aria-labelledby="enrollRegularStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="enrollRegularStudentModalLabel">Enroll Student <?php echo ' | ' . $enrolledProgramToSection['data'][0]['educational_level'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="enrollRegularStudentModalForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="enrollStudentsAsRegularToSection">
                    <input type="hidden" id="inp_educational_level" name="educational_level" value="<?php echo $enrolledProgramToSection['data'][0]['educational_level'] ?>">
                    <input type="hidden" id="section_id" name="section_id" value="<?php echo $_GET['viewSection'] ?>">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-12 d-flex flex-column">
                            <label for="input_enrollSubject" class="form-label">Enrollment Type</label>
                            <ul>
                                <li>Regular</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-12 d-flex flex-column">
                            <label for="input_enrollStudents" class="form-label">Students</label>
                            <select class="form-select" id="input_enrollStudents" name="input_enrollStudents[]" multiple></select>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Initialize select2 with AJAX configuration
                            $("#input_enrollStudents").select2({
                                dropdownParent: $('#enrollRegularStudentModal'),
                                width: "100%",
                                placeholder: "Search subjects to add", // Placeholder text
                                allowClear: true, // Allow clearing the selection
                                ajax: {
                                    url: "", // Empty URL to use the current URL
                                    type: "POST",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params) {
                                        console.log($("#inp_educational_level").val());

                                        return {
                                            search_type: "student",
                                            query: params.term, // Search query from user input
                                            additional_filters: {
                                                educational_level: $("#inp_educational_level").val(), // Filter by educational level
                                            },
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data.map(function(student) {
                                                return {
                                                    id: student.user_id,
                                                    text: `${student.name} | ${student.user_id}`
                                                };
                                            })
                                        };
                                    }
                                }
                            });
                        });
                    </script>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary c-primary" form="enrollRegularStudentModalForm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>