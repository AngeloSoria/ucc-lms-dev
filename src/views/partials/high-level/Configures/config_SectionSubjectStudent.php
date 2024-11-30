<?php
$enrolledSubjectSectionInfo = $subjectSectionController->getSubjectSectionDetails($_GET['subject_section_id']);
$enrolledStudentsFromSubjectSection = $subjectSectionController->getEnrolledStudentsFromSubject($_GET['subject_section_id']);

if (!$enrolledStudentsFromSubjectSection['success']) {
    $_SESSION["_ResultMessage"] = $enrolledStudentsFromSubjectSection['message'];
}
?>
<div class="mb-3 row align-items-start">
    <hr>
    <div class="tab-content" id="myTabContent">
        <div class="" id="information">
            <div class="position-relative p-3">
                <div class="card-body">
                    <div class="mb-5">
                        <h5 class="fs-5 text-center"><?php echo $enrolledSubjectInformation['data']['subject_name']; ?></h5>
                        <p class="fs-6 text-center">Enrollment Overview</p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 gap-2 d-flex justify-content-end align-items-center">
                            <span class="btn btn-success" role="button" data-bs-toggle="modal" data-bs-target="#enrollRegularStudentModal">
                                <i class="bi bi-person-fill-add"></i>
                                Enroll Students
                            </span>
                            <span class="btn btn-danger btn-sm">
                                <i class="bi bi-trash-fill"></i>
                                Remove Selected
                            </span>
                        </div>
                        <div class="col-md-12">
                            <p class="fs-6 fw-semibold mb-2">Enrolled Students</p>
                            <table id="dataTable_enrolledSubjects"
                                class="table table-responsive table-hover border table-bordered"
                                style="width: 100%">
                                <caption>Table of Enrolled Students</caption>
                                <thead class="table-brand-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 5%;"><input type="checkbox" id="checkbox_selectAll" class="form-check-input" accesskey="" value="<?php echo $_GET['viewSection'] ?>"></th>
                                        <th class="text-center" style="width: 15%;">User Id</th>
                                        <th class="" style="width: auto;">Student Name</th>
                                        <th class="text-center" style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($enrolledStudentsFromSubjectSection['data'])) { ?>
                                        <tr>
                                            <td class="text-center" colspan="5">No Enrolled Students</td>
                                            <td class="text-center d-none"></td>
                                            <td class="text-center d-none"></td>
                                            <td class="text-center d-none"></td>
                                        </tr>
                                    <?php } else { ?>
                                        <?php foreach ($enrolledStudentsFromSubjectSection['data'] as $enrolledStudentInfo) {
                                        ?>
                                            <tr class="table-default">
                                                <td class="text-center text-truncate"><input type="checkbox" title="selectAll" class="form-check-input"></td>
                                                <td class="align-center text-center">
                                                    <a title="View Subject" class="badge badge-secondary" href="<?php echo "users_admin.php?viewRole=Student&user_id=" . $enrolledStudentInfo['user_id'] ?>">
                                                        <?php echo $enrolledStudentInfo['user_id'] ?>
                                                    </a>
                                                </td>
                                                <td class="" contenteditable="true"><?php echo $enrolledStudentInfo['first_name'] . " " . $enrolledStudentInfo['middle_name'] . " " . $enrolledStudentInfo['last_name'] ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:alert('work in progress')" title="remove"
                                                        class="btn btn-sm btn-danger m-auto">
                                                        <i class="bi bi-trash-fill"></i>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ENROLL STUDENT MODAL -->
<div class="modal fade" id="enrollRegularStudentModal" tabindex="-1" aria-labelledby="enrollRegularStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="enrollRegularStudentModalLabel">Enroll Students<?php echo ' | ' . $enrolledProgramToSection['data'][0]['educational_level'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="enrollRegularStudentModalForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="updateEnrolledStudentsFromSection">
                    <input type="hidden" id="inp_educational_level" name="educational_level" value="<?php echo $enrolledProgramToSection['data'][0]['educational_level'] ?>">
                    <input type="hidden" id="section_id" name="section_id" value="<?php echo $_GET['viewSection'] ?>">
                    <input type="hidden" id="subject_section_id" name="subject_section_id" value="<?php echo $enrolledProgramToSection['data'][0]['educational_level'] ?>">
                    <div class="mb-3 d-flex gap-2">
                        <div class="col-md-12 d-flex flex-column">
                            <label for="input_enrollStudents" class="form-label">Students</label>
                            <select class="form-select d-flex flex-wrap" id="input_enrollStudents" name="input_enrollStudents[]" multiple></select>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Initialize select2 with AJAX configuration
                            $("#input_enrollStudents").select2({
                                dropdownParent: $('#enrollRegularStudentModal'),
                                width: "100%",
                                placeholder: "Search students to add", // Placeholder text
                                allowClear: true, // Allow clearing the selection
                                ajax: {
                                    url: "", // Empty URL to use the current URL
                                    type: "POST",
                                    dataType: "json",
                                    delay: 250,
                                    data: function(params) {
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