<div class="mb-3 row align-items-start">
    <hr>
    <ul class="nav nav-tabs border-bottom px-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab" aria-controls="information" aria-selected="true">Information</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">Students</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">Subjects</button>
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
                                <div class="card p-3 text-white fs-7 bg-danger mt-sm-3">
                                    <div class="mb-1 border-bottom d-flex justify-content-between align-center">
                                        <span class="fs-6 fw-semibold">Disclaimer</span>
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
                    <div class="">
                        <button type="submit" class="btn btn-success d-flex gap-2">
                            <i class="bi bi-floppy-fill"></i>
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade pt-2" id="students" role="tabpanel" aria-labelledby="students-tab">
            <form method="POST">
                <input type="hidden" name="action" value="updateEnrolledStudentsFromSection">
                <div class="col-md-12 p-3 rounded">
                    <section class="role_table">
                        <h5>Student Enrollment</h5>
                        <!-- =============================================== -->
                        <!-- DATA TABLE BY STUDENTS -->
                        <section class="d-flex justify-content-between mb-2 p-1 bg-transparent">
                            <div class="d-flex justify-content-start align-items-end flex-grow-1">
                                <span class="btn btn-sm btn-danger disabled">
                                    <i class="bi bi-trash"></i>
                                    Remove Selected
                                </span>
                            </div>
                            <div id="searchInput_students" class="card col-md-6 row shadow-sm rounded p-2 border">
                                <div class="d-flex flex-column gap-2">
                                    <span for="input_AddStudentsToEnrollFromSection">Add Students</span>
                                    <select class="form-select" id="input_AddStudentsToEnrollFromSection" name="input_AddStudentsToEnrollFromSection[]" multiple></select>
                                    <div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-person-fill-add"></i>
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    // Initialize select2 with AJAX configuration
                                    $("#input_AddStudentsToEnrollFromSection").select2({
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
                                                        educational_level: $("#input_sectionEducationalLevel").val(), // Filter by educational level
                                                    },
                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: data.map(function(student) {
                                                        return {
                                                            id: student.user_id,
                                                            text: `${student.name} (${student.user_id})`
                                                        };
                                                    })
                                                };
                                            }
                                        }
                                    });
                                });
                            </script>
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
                                    <!-- <th class="text-center" style="width: 10rem;">Enrolled Type</th> -->
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
                                            <!-- <td class="text-center align-center"><?php echo ucfirst($userData['enrollment_type']) ?></td> -->
                                            <td class="text-center">
                                                <a href="javascript:alert('work in progress')" title="Remove"
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
            </form>
        </div>
        <div class="tab-pane fade pt-2" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
            <form method="POST">
                <div class="col-md-12 p-2 rounded">
                    <section class="role_table">
                        <h5>Subject Enrollment</h5>
                        <!-- =============================================== -->
                        <!-- DATA TABLE BY STUDENTS -->
                        <section class="row mb-2 p-1 bg-transparent">
                            <div class="col-md-4 cold-md-4 col-lg-5 d-flex justify-content-start align-items-end">
                                <span class="btn btn-sm btn-danger disabled">
                                    <i class="bi bi-trash"></i>
                                    Remove Selected
                                </span>
                            </div>
                            <div class="bg-warning-subtle col-sm-12 col-md-9 col-lg-7 mt-sm-2 row shadow-sm rounded p-2 border">
                                <div class="col-md-6">
                                    <span for="input_addToEnrolSubjects" class="fw-semibold">Select Teacher:</span>
                                    <select disabled class="form-select border-0 m-0" id="input_addTeacherToSubjectEnrollment" name="input_addTeacherToSubjectEnrollment[]"></select>
                                </div>
                                <div class="col-md-6">
                                    <span for="input_addToEnrolSubjects" class="fw-semibold">Add Subject:</span>
                                    <select disabled class="form-select border-0 m-0" id="input_addToEnrollSubjects" name="input_addToEnrollSubjects[]"></select>
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
                                                    <a href="javascript:alert('work in progress')" title="Remove"
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
            </form>
        </div>
    </div>
</div>