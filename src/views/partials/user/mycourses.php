<?php
$fakedata_enrolled_subjects2 = [
    [
        'subject_id' => 3001,
        'subject_code' => 'ITMA1223',
        'subject_name' => 'Data Structures & Algorithms',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
    [
        'subject_id' => 3002,
        'subject_code' => 'PHYS4412',
        'subject_name' => 'Physical Education 2',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_PE.jpg'
    ],
    [
        'subject_id' => 3003,
        'subject_code' => 'LERP1337',
        'subject_name' => 'Information Assurance and Security (Data Privacy)',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
    [
        'subject_id' => 3004,
        'subject_code' => 'MATH1124',
        'subject_name' => 'Calculus II',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Math.jpg'
    ],
    [
        'subject_id' => 3005,
        'subject_code' => 'CHEM2011',
        'subject_name' => 'Organic Chemistry',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Chem.jpg'
    ],
    [
        'subject_id' => 3006,
        'subject_code' => 'HIST3010',
        'subject_name' => 'World History',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_History.jpg'
    ],
    [
        'subject_id' => 3007,
        'subject_code' => 'PSYC2210',
        'subject_name' => 'Introduction to Psychology',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Psychology.jpg'
    ],
    [
        'subject_id' => 3008,
        'subject_code' => 'CSCI1101',
        'subject_name' => 'Introduction to Computer Science',
        'subject_section' => 'BSIT701P',
        'subject_image' => 'img/client-images/program_Tech.jpg'
    ],
];
?>
<!-- Container -->
<div class="bg-white shadow-sm rounded p-3 border border-box mb-sm-2 d-flex flex-column" id="main-container" style="max-height: 350px;">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-5 fw-semibold text-success m-0">My Subjects</p>
        <div class="container-controls">
            <button class="btn btn-transparent" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mycourses_dropdown">
                <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleCoursesView(this);">Tile View</a></li>
            </ul>
        </div>
    </div>
    <hr class="opacity-80 mx-0 my-2">

    <!-- Inner Content -->
    <div class="bg-transparent h-100">
        <div id="container_listview">
            <ul class="list-group list-group-flush overflow-y-auto overflow-x-hidden" style="max-height: 240px;">
                <?php if (!empty($myEnrolledSubjects['data'])) { ?>
                    <?php foreach ($myEnrolledSubjects['data'] as $subject) {
                        $subjectInfo = $subjectController->getSubjectFromSubjectId($subject['subject_id']);
                        $sectionInfo = $sectionController->getSectionById($subject['section_id']);
                    ?>
                        <li class="list-group-item box border-box bg-transparent p-0 py-1">
                            <div class="row bg-transparent p-1">
                                <div class="col-sm-9 col-md-6 col-lg-6 d-flex justify-content-start align-items-center gap-2">
                                    <div id="itemIconContainer" class="position-relative rounded" style="width: 50px; height: 50px;">
                                        <?php if (!empty($subject['subject_section_image'])): ?>
                                            <img src="<?php echo "data:image/jpeg;base64," . base64_encode($subject['subject_section_image']) ?>" class="object-fit-fill position-absolute w-100 h-100 rounded border border-success" style="height: 120px;">
                                        <?php else: ?>
                                            <img src="<?php echo asset('img/placeholder-1.jpg') ?>" class="object-fit-fill position-absolute w-100 h-100 rounded border border-success" style="height: 120px;">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="<?= "subject_view.php?subject_section_id=" . $subject['subject_section_id'] ?>" class="link link-body-emphasis">
                                            <p class="fs-6 p-0 m-0"><?php echo $subjectInfo['data']['subject_name'] . ' (' . $subjectInfo['data']['subject_code'] . ')' ?></p>
                                        </a>
                                        <a href="#" class="link-dark">
                                            <p class="fs-7 p-0 m-0"><?php echo $sectionInfo['data']['section_name'] ?></p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-4 col-lg-6 d-flex align-items-center justify-content-end">
                                    <div class="d-flex gap-3 fw-semibold">
                                        <div class="d-flex gap-1 fs-7 align-items-center bg-primary bg-opacity-75 px-2 rounded-pill text-white" title="Grades">
                                            <p>87</p>
                                            <div class="icon"><i class="bi bi-percent"></i></div>
                                        </div>
                                        <div class="d-flex gap-1 fs-7 align-items-center bg-danger bg-opacity-75 px-2 rounded-pill text-white" title="Number of Modules">
                                            <p>1</p>
                                            <div class="icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <p class="fs-6 m-auto p-4">No enrolled subjects...</p>
                <?php } ?>
            </ul>
        </div>
        <div id="container_tileview" class="d-none h-100 row d-flex">
            <?php foreach ($myEnrolledSubjects['data'] as $subject) {
                $subjectInfo = $subjectController->getSubjectFromSubjectId($subject['subject_id']);
                $sectionInfo = $sectionController->getSectionById($subject['section_id']);
            ?>
                <div class="col-md-6 col-lg-4 p-1" style="height: 250px;">
                    <a href="#">
                        <div id="item_card" class="h-100 w-100 bg-success bg-opacity-80 shadow-sm border rounded overflow-hidden d-flex flex-column">
                            <div>
                                <img src="<?php echo asset('img/placeholder-1.jpg') ?>" class="w-100 object-fit-cover" style="height: 120px;">
                            </div>
                            <div class="px-2 flex-grow-1 position-relative">
                                <p class="fs-6 text-white pt-2 fw-semibold">
                                    <?php echo $subjectInfo['data']['subject_name'] ?>
                                </p>
                                <p class="fs-7 text-white position-absolute bottom-0 start-0 ms-2 mb-2">
                                    <?php echo $sectionInfo['data']['section_name'] ?>
                                </p>
                                <div class="d-flex position-absolute bottom-0 end-0 me-2 mb-2">
                                    <div class="d-flex gap-1 fs-7 align-items-center bg-primary bg-opacity-75 px-2 rounded-pill text-white" title="Grades">
                                        <p>87</p>
                                        <div class="icon"><i class="bi bi-percent"></i></div>
                                    </div>
                                    <div class="d-flex gap-1 fs-7 align-items-center bg-danger bg-opacity-75 px-2 rounded-pill text-white" title="Number of Modules">
                                        <p>1</p>
                                        <div class="icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    function toggleCoursesView(btn) {
        const mainContainer = document.getElementById('main-container');
        const tileview = document.getElementById('container_tileview');
        const listview = document.getElementById('container_listview');

        if (btn.textContent.trim() === "Tile View") {
            btn.textContent = "List View";
            tileview.classList.remove("d-none");
            listview.classList.add("d-none");
            mainContainer.style.maxHeight = "none"; // Allow auto scaling
        } else if (btn.textContent.trim() === "List View") {
            btn.textContent = "Tile View";
            listview.classList.remove("d-none");
            tileview.classList.add("d-none");
            mainContainer.style.maxHeight = "350px"; // Set fixed height for list view
        }
    }
</script>