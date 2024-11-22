<!-- Container -->
<div class="bg-white shadow-sm rounded p-4 border border-box mb-sm-2 d-flex flex-column" id="main-container" style="max-height: 350px;">
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

    <!-- Inner Content -->
    <div class="bg-transparent h-100">
        <div id="container_listview">
            <ul class="list-group list-group-flush overflow-y-auto overflow-x-hidden" style="max-height: 240px;">
                <?php for ($i = 0; $i < 5; $i++) { ?>
                    <li class="list-group-item box border-box bg-transparent p-0 py-1">
                        <div class="row bg-transparent p-1">
                            <div class="col-sm-8 col-md-6 col-lg-6 d-flex justify-content-start align-items-center gap-2">
                                <div id="itemIconContainer" class="position-relative rounded" style="width: 40px; height: 40px;">
                                    <img class="object-fit-fill position-absolute w-100 h-100 rounded" src="<?php echo asset('img/placeholder-1.jpg') ?>" alt="qq">
                                </div>
                                <div>
                                    <a href="#" class="link link-underline">
                                        <p class="title fs-6 p-0 m-0 fw-semibold">Data Structures & Algorithms III</p>
                                    </a>
                                    <a href="#" class="link-dark">
                                        <p class="subtitle fs-7 p-0 m-0">BSIT701P</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-6 d-flex align-items-center justify-content-end">
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
            </ul>
        </div>
        <div id="container_tileview" class="d-none h-100 row d-flex">
            <?php for ($i = 0; $i < 5; $i++) { ?>
                <div class="col-md-6 col-lg-4 p-1" style="height: 250px;">
                    <a href="#">
                        <div id="item_card" class="h-100 w-100 bg-success bg-opacity-80 shadow-sm border rounded overflow-hidden d-flex flex-column">
                            <div>
                                <img src="<?php echo asset('img/placeholder-1.jpg') ?>" class="w-100 object-fit-cover" style="height: 120px;">
                            </div>
                            <div class="px-2 flex-grow-1 position-relative">
                                <p class="fs-6 text-white pt-2 fw-semibold">
                                    Data Structures & Algorithms III
                                </p>
                                <p class="fs-7 text-white position-absolute bottom-0 start-0 ms-2 mb-2">
                                    BSIT701P
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