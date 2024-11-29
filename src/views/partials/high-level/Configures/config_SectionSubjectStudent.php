<?php

?>
<div class="mb-3 row align-items-start">
    <hr>
    <div class="tab-content" id="myTabContent">
        <div class="" id="information">
            <form method="POST">
                <input type="hidden" name="action" value="updateSectionInfo">
                <div class="position-relative p-3">
                    <div class="card-body">
                        <div class="row mb-4">
                            <h5 class="display-7 text-start">Subject Overview</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="fs-5 mb-2">Enrolled Students</p>
                                <table id="dataTable_enrolledSubjects"
                                    class="table table-responsive table-hover border table-bordered"
                                    style="width: 100%">
                                    <caption>Table of Enrolled Students</caption>
                                    <thead class="table-brand-secondary">
                                        <tr>
                                            <th class="text-center" style="max-width: 1%;"><input type="checkbox" id="checkbox_selectAll" class="form-check-input" accesskey="" value="<?php echo $_GET['viewSection'] ?>"></th>
                                            <th class="text-center" style="max-width: 5%;">Subject Id</th>
                                            <th>Subject Name</th>
                                            <th>Instructor</th>
                                            <th>No. Enrolled Students</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($enrolledStudentInfoFromSection)) { ?>
                                            <tr>
                                                <td class="text-center" colspan="6">No Enrolled Subjects</td>
                                                <td class="text-center d-none"></td>
                                                <td class="text-center d-none"></td>
                                                <td class="text-center d-none"></td>
                                                <td class="text-center d-none"></td>
                                                <td class="text-center d-none"></td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($enrolledStudentInfoFromSection as $enrolledStudentInfo) {
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
                                                    <td class=""><?php echo 1337 ?></td>
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
                    <hr>
                    <div class="float-end d-flex gap-2">
                        <span type="button" class="btn btn-danger d-flex gap-2" id="btnDelete">
                            <i class="bi bi-trash-fill"></i>
                            Unenroll Subject
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>