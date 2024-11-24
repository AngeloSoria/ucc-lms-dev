<div class="mb-3 row align-items-start">
    <hr>
    <div class="container my-4">
        <form dynamic-form-id="edit_Section" class="mb-4" method="POST">
            <h4 class="fw-bolder text-success">Edit Section</h4>
            <div class="card shadow-sm position-relative">
                <div
                    class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
                    <div class="position-absolute top-0 end-0 mt-4 me-4 d-flex gap-2">
                        <span id="dynamic_btn_edit" class="btn btn-sm cbtn-secondary d-flex gap-2">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </span>
                        <button type="submit" id="dynamic_btn_save" class="btn btn-sm btn-success d-flex gap-2 d-none">
                            <i class="bi bi-floppy-fill"></i>
                            Save
                        </button>
                        <span id="dynamic_btn_cancel"
                            class="btn btn-sm btn-danger d-flex gap-2 d-none">
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="action" value="updateSection">
                    <input type="hidden" name="section_id" id="section_id" value="<?php echo $retrievedSection['data']['section_id'] ?>">
                    <div class="row mb-3">
                        <h5>Section Information</h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 col-lg-4 mb-3">
                            <h6>Section Name</h6>
                            <input update-enabled name="input_sectionName" class="form-control"
                                type="text" disabled
                                value="<?= htmlspecialchars($retrievedSection['data']['section_name']) ?>">
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <h6 class="text-truncate">Educational Level</h6>
                            <select name="input_sectionEducationalLevel"
                                id="input_sectionEducationalLevel" class="form-select" disabled>
                                <?php
                                $option1 = htmlspecialchars($enrolledProgramsOfSection['data'][0]['educational_level']);
                                $option2 = $option1 == "College" ? "SHS" : "College";
                                ?>
                                <option value="<?php echo $option1 ?>"><?php echo $option1 ?>
                                </option>
                                <option value="<?php echo $option2 ?>"><?php echo $option2 ?>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-5 mb-3">
                            <h6>Program</h6>
                            <select update-enabled name="input_sectionProgram"
                                id="input_sectionPrograms" class="form-select" disabled
                                title="Enrolled Program">
                                <?php if ($retrievedSection['success']): ?>
                                    <?php if (!empty($enrolledProgramsOfSection['data'][0])): ?>
                                        <?php if ($enrolledProgramsOfSection['data'][0]['educational_level'] == "College"): ?>
                                            <?php if ($retrievedAllPrograms['success']): ?>
                                                <?php foreach ($retrievedAllPrograms['data'] as $programs): ?>
                                                    <?php if ($programs['educational_level'] == $enrolledProgramsOfSection['data'][0]['educational_level']): ?>
                                                        <option <?php echo ($enrolledProgramsOfSection['data'][0]['program_id'] == $programs['program_id']) ? "selected" : "" ?>
                                                            value="<?php echo $programs['program_id'] ?>">
                                                            <?php echo htmlspecialchars($programs['program_code'] . " | " . $programs['program_name']) ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <option value="null">Nothing selected</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <h6>Semester</h6>
                            <select class="form-select" name="" id="" disabled>
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
                        <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                            <h6>Year Level</h6>
                            <!-- <input update-enabled class="form-control" type="text" disabled value="<?= htmlspecialchars($retrievedSection['data']['year_level']) ?>"> -->
                            <select update-enabled class="form-select"
                                name="input_sectionYearLevel" id="input_sectionYearLevel"
                                disabled>
                                <?php if ($retrievedSection['success']): ?>
                                    <option
                                        value="<?= htmlspecialchars($retrievedSection['data']['year_level']) ?>">
                                        <?= htmlspecialchars($retrievedSection['data']['year_level']) ?>
                                    </option>
                                    <?php if ($retrievedSection['data']['year_level'] == 1): ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 col-lg-7">
                            <h6>Class Adviser</h6>
                            <select update-enabled class="form-select"
                                name="input_sectionAdviser" id="input_sectionAdviser" disabled>
                                <?php if ($enrolledAdviserToSection['success']): ?>
                                    <option value="<?php echo $enrolledAdviserToSection['data']['user_id'] ?>">
                                        <?php echo $enrolledAdviserToSection['data']['first_name'] . ' ' . $enrolledAdviserToSection['data']['last_name'] ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <hr class="mt-5">

                    <div class="row mb-3 mt-3">
                        <div class="col-md-12 p-2 rounded">
                            <section class="role_table">
                                <h5>Student Enrollment</h5>
                                <!-- =============================================== -->
                                <!-- DATA TABLE BY STUDENTS -->
                                <section class="row mb-2 p-1 bg-transparent">
                                    <div class="col-md-4 cold-md-4 col-lg-6 d-flex justify-content-start align-items-end">
                                        <span update-enabled class="btn btn-sm btn-danger disabled">
                                            <i class="bi bi-trash"></i>
                                            Remove Selected
                                        </span>
                                    </div>
                                    <div id="searchInput_students" class="bg-warning-subtle col-sm-12 col-md-8 col-lg-6 mt-sm-2 row shadow-sm rounded p-2 border">
                                        <div class="col-md-4">
                                            <span for="input_sectionEnrollmentType" class="fw-semibold">Enrollment Type:</span>
                                            <select update-enabled disabled class="form-select" id="input_sectionEnrollmentType" name="input_sectionEnrollmentType">
                                                <option value="Regular" selected>Regular</option>
                                                <option value="Irregular">Irregular</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <span for="input_modalAddToEnrollStudents" class="fw-semibold">Add Students:</span>
                                            <select update-enabled disabled class="form-select border-0 m-0" id="input_modalAddToEnrollStudents" name="input_modalAddToEnrollStudents[]" multiple></select>
                                        </div>
                                    </div>
                                </section>


                                <table id="dataTable_enrolledStudents"
                                    class="table table-responsive table-bordered table-hover table-sm border"
                                    style="width: 100%">
                                    <caption>Table of Enrolled Students</caption>
                                    <thead class="table-brand-secondary">
                                        <tr>
                                            <th class="text-center" style="width: 1.5rem;"><input type="checkbox" id="checkbox_selectAll" class="form-check-input" accesskey="" value="enrolledStudentsID_<?php echo $_GET['viewSection'] ?>"></th>
                                            <th class="text-center" style="max-width: 6rem;">User Id</th>
                                            <th>Student Name</th>
                                            <th class="text-center" style="width: 10rem;">Enrolled Type</th>
                                            <th class="text-center" style="width: 5rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($enrolledStudentInfoFromSection)) { ?>
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">No Enrolled Students</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                            </tr>
                                        <?php } else { ?>

                                            <?php foreach ($enrolledStudentInfoFromSection as $userData) { ?>
                                                <tr class="table-default">
                                                    <td class="text-center"><input type="checkbox" title="selectAll" class="form-check-input" value="<?php htmlspecialchars($userData['user_id'] ?? '') ?>"></td>
                                                    <td class="align-center text-center">
                                                        <a title="View User" class="btn btn-sm bg-brand-secondary bg-opacity-25 ctxt-secondary fw-semibold rounded-pill" href="users_admin.php<?php echo htmlspecialchars('?viewRole=' . $userData['role'] . '&user_id=' . $userData['user_id']) ?>">
                                                            <?php echo $userData['user_id'] ?>
                                                        </a>
                                                    </td>
                                                    <td class=""><?php echo $userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name'] ?></td>
                                                    <td class="text-center align-center"><?php echo ucfirst($userData['enrollment_type']) ?></td>
                                                    <td class="text-center">
                                                        <a update-enabled href="javascript:alert('work in progress')" title="Remove"
                                                            class="btn btn-sm btn-danger m-auto disabled">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    $(document).ready(function() {
                                        // Initialize DataTable
                                        $('#dataTable_enrolledStudents').DataTable({
                                            columnDefs: [{
                                                "orderable": false,
                                                "targets": [0, 3]
                                            }],
                                            language: {
                                                "paginate": {
                                                    previous: '<span class="bi bi-chevron-left"></span>',
                                                    next: '<span class="bi bi-chevron-right"></span>'
                                                },
                                                "lengthMenu": '<select class="form-control input-sm">' +
                                                    '<option value="5">5</option>' +
                                                    '<option value="10">10</option>' +
                                                    '<option value="20">20</option>' +
                                                    '<option value="30">30</option>' +
                                                    '<option value="40">40</option>' +
                                                    '<option value="50">50</option>' +
                                                    '<option value="-1">All</option>' +
                                                    '</select> Entries per page',
                                            },
                                            initComplete: function() {
                                                this.api()
                                                    .columns([3])
                                                    .every(function() {
                                                        var column = this;

                                                        // Create select element and listener
                                                        var select = $('<select class="form-select"><option value=""></option></select>')
                                                            .appendTo($(column.footer()).empty())
                                                            .on('change', function() {
                                                                var val = $.fn.dataTable.util.escapeRegex($(this).val()); // Escape regex for exact matching
                                                                column
                                                                    .search(val ? '^' + val + '$' : '', true, false) // Exact match with regex
                                                                    .draw();
                                                            });

                                                        // Add list of options
                                                        column
                                                            .data()
                                                            .unique()
                                                            .sort()
                                                            .each(function(d, j) {
                                                                select.append(
                                                                    '<option value="' + d + '">' + d + '</option>'
                                                                );
                                                            });
                                                    });
                                            }
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
                        <hr class="mt-5">
                        <div class="col-md-12 p-2 rounded">
                            <section class="role_table">
                                <h5>Subject Enrollment</h5>
                                <!-- =============================================== -->
                                <!-- DATA TABLE BY STUDENTS -->
                                <section class="row mb-2 p-1 bg-transparent">
                                    <div class="col-md-4 cold-md-4 col-lg-5 d-flex justify-content-start align-items-end">
                                        <span update-enabled class="btn btn-sm btn-danger disabled">
                                            <i class="bi bi-trash"></i>
                                            Remove Selected
                                        </span>
                                    </div>
                                    <div class="bg-warning-subtle col-sm-12 col-md-9 col-lg-7 mt-sm-2 row shadow-sm rounded p-2 border">
                                        <div class="col-md-6">
                                            <span for="input_addToEnrolSubjects" class="fw-semibold">Select Teacher:</span>
                                            <select update-enabled disabled class="form-select border-0 m-0" id="input_addTeacherToSubjectEnrollment" name="input_addTeacherToSubjectEnrollment[]"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <span for="input_addToEnrolSubjects" class="fw-semibold">Add Subject:</span>
                                            <select update-enabled disabled class="form-select border-0 m-0" id="input_addToEnrollSubjects" name="input_addToEnrollSubjects[]"></select>
                                        </div>
                                    </div>
                                </section>


                                <table id="dataTable_enrolledSubjects"
                                    class="table table-responsive table-bordered table-hover table-sm border"
                                    style="width: 100%">
                                    <caption>Table of Enrolled Subjects</caption>
                                    <thead class="table-brand-secondary">
                                        <tr>
                                            <th class="text-center" style="width: 1.5rem;"><input type="checkbox" id="checkbox_selectAll" class="form-check-input" accesskey="" value="enrolledStudentsID_<?php echo $_GET['viewSection'] ?>"></th>
                                            <th class="text-center" style="max-width: 6rem;">Subject Id</th>
                                            <th>Subject Name</th>
                                            <th class="text-center" style="width: 5rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($enrolledSubjectsFromSection['data'])) { ?>
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">No Enrolled Subjects</td>
                                                <td class="text-center"></td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($enrolledSubjectsFromSection['data'] as $subjectData) {
                                                $subjectInfo = $subjectController->getSubjectFromSubjectId($subjectData['subject_id']);
                                                if ($subjectInfo['success']):
                                            ?>
                                                    <tr class="table-default">
                                                        <td class="text-center"><input type="checkbox" title="selectAll" class="form-check-input" value="<?php htmlspecialchars($subjectData['subject_id'] ?? '') ?>"></td>
                                                        <td class="align-center text-center">
                                                            <a title="View Subject" class="btn btn-sm bg-primary bg-opacity-25 text-primary fw-semibold rounded-pill" href="users_admin.php<?php echo htmlspecialchars('?viewSubject=' . $subjectData['subject_id']) ?>">
                                                                <?php echo $subjectInfo['data'][0]['subject_id'] ?>
                                                            </a>
                                                        </td>
                                                        <td class=""><?php echo $subjectInfo['data'][0]['subject_name'] ?></td>
                                                        <td class="text-center">
                                                            <a update-enabled href="javascript:alert('work in progress')" title="Remove"
                                                                class="btn btn-sm btn-danger m-auto disabled">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                        <?php endif;
                                            }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    $(document).ready(function() {
                                        // Initialize DataTable
                                        $('#dataTable_enrolledSubjects').DataTable({
                                            columnDefs: [{
                                                "orderable": false,
                                                "targets": [0]
                                            }],
                                            language: {
                                                "paginate": {
                                                    previous: '<span class="bi bi-chevron-left"></span>',
                                                    next: '<span class="bi bi-chevron-right"></span>'
                                                },
                                                "lengthMenu": '<select class="form-control input-sm">' +
                                                    '<option value="5">5</option>' +
                                                    '<option value="10">10</option>' +
                                                    '<option value="20">20</option>' +
                                                    '<option value="30">30</option>' +
                                                    '<option value="40">40</option>' +
                                                    '<option value="50">50</option>' +
                                                    '<option value="-1">All</option>' +
                                                    '</select> Entries per page',
                                            },
                                            initComplete: function() {
                                                this.api()
                                                    .columns([])
                                                    .every(function() {
                                                        var column = this;

                                                        // Create select element and listener
                                                        var select = $('<select class="form-select"><option value=""></option></select>')
                                                            .appendTo($(column.footer()).empty())
                                                            .on('change', function() {
                                                                var val = $.fn.dataTable.util.escapeRegex($(this).val()); // Escape regex for exact matching
                                                                column
                                                                    .search(val ? '^' + val + '$' : '', true, false) // Exact match with regex
                                                                    .draw();
                                                            });

                                                        // Add list of options
                                                        column
                                                            .data()
                                                            .unique()
                                                            .sort()
                                                            .each(function(d, j) {
                                                                select.append(
                                                                    '<option value="' + d + '">' + d + '</option>'
                                                                );
                                                            });
                                                    });
                                            }
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
                    </div>
                </div>
        </form>
    </div>
</div>

<?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Section']['Config']['addStudent']) ?>