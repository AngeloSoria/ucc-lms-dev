<?php
// Table: subject_section
$fake_subjects = [
    [
        // Main Columns
        'subject_section_id' => 1,
        'subject_id' => 40042,
        'section_id' => 30021,
        'calendar_id' => 50012,
        'subject_section_image' => 'img/placeholder-1.jpg',

        // From foreign columns
        'subject_code' => 'ITSM1341',
        'subject_name' => 'Data Structure & Algorithms',
        'section_name' => 'BSIT701P',
        'calendar_name' => '2022-2023',

        // calculations
        'total_due' => 4,
        'grade' => [
            'scale' => 1.5,
            'percentage' => 90
        ],
    ]
];
?>
<div class="container-fluid bg-light shadow-sm rounded p-4 border">
    <div class="d-flex justify-content-between align-items-center">
        <p class="fs-4 fw-semibold text-success m-0">My Subjects</p>
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

    <!-- Container -->
    <div class="d-flex container-fluid flex-wrap gap-3 mt-3">
        <div id="courses_listview">

        </div>
        <!-- <div id="courses_tileview"></div> -->
    </div>
</div>
<script>
    function toggleCoursesView(btn) {
        // Toggle tile view or list view
        // Update the button text and icon accordingly
        console.log(btn);
    }
</script>