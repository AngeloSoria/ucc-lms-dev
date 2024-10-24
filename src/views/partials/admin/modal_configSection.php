<div class="modal fade" id="configSectionModal" tabindex="-1" aria-labelledby="configSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-top">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ctxt-primary" id="configSectionModalLabel">Section Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <h4>BSIT 701P</h4>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-inline">
                            <b>Program ID:</b>
                            <span class="m-0">10</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-inline">
                            <b>Year Level:</b>
                            <span class="m-0">4</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-inline">
                            <b>Semester:</b>
                            <span class="m-0">2</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-inline">
                            <b>Adviser ID:</b>
                            <span class="m-0">John Doe (1003)</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between gap-1 mb-2">
                            <h6>Enrolled Students (25)</h6>
                            <!-- <button class="btn btn-primary c-primary py-1 px-2 m-0 d-flex align-items-center gap-2">
                                <i class="bi bi-plus-circle fs-5"></i>
                                Add Student
                            </button> -->
                        </div>
                        <div class="position-relative" id="special_search">
                            <div class="search-box input-group">
                                <input type="text" class="form-control rounded-0 border" id="searchStudent" placeholder="Search">
                                <label for="searchStudent" class="input-group-text bg-success text-white">
                                    <i class="bi bi-search"></i>
                                </label>
                            </div>
                            <!-- search box -->
                            <div id="searchBox_student" class="d-none z-1 position-absolute bg-light border pt-1 px-0 m-0 w-100 shadow-lg overflow-y-auto" style="min-height: 150px; max-height: 150px;">
                                <!-- queried item -->
                                <div role="button" class="bg-success border p-2 text-truncate d-flex justify-content-start align-items-center gap-3" onclick="console.log(this)">
                                    <img class="w-10" src="<?php echo BASE_PATH ?>src/assets/images/icons/avatars/profile-avatar-1.png" alt="profile image">
                                    <p class="text-truncate flex-grow-1 m-0">Angelo Soria (2000166505)</p>
                                    <div>
                                        <button class="btn btn-transparent">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <style>
                            #special_search:has(#searchStudent:focus) #searchBox_student,
                            #special_search:has(#searchBox_student *:active) #searchBox_student {
                                display: block !important;
                            }
                        </style>
                        <div class="mt-1 container border p-0 overflow-y-auto position-relative" style="min-height: 250px; max-height: 250px;">
                            <table class="table table-striped table-hover">
                                <thead class="bg-success-subtle">
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td>1</td>
                                        <td>Angelo Soria</td>
                                        <td>2000166505</td>
                                        <td>
                                            <button class="btn btn-danger py-1 px-2 m-0 d-flex align-items-center gap-2">
                                                <i class="bi bi-trash"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Enrolled Subjects (8)</h6>
                        <div class="container border rounded p-0 overflow-y-auto position-relative" style="min-height: 250px; max-height: 250px;">
                            <div class="search-box mb-1 position-sticky top-0 start-0">
                                <input type="text" class="form-control rounded-0 border" id="searchStudent" placeholder="Search">
                            </div>
                            <ul class="list-unstyled p-0 m-0 d-flex flex-column gap-1">
                                <li class="bg-dark-subtle border p-2 text-truncate d-flex justify-content-start align-items-center gap-3">
                                    <p class="text-truncate flex-grow-1 m-0">Information Assurance</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary c-primary">Submit</button>
            </div>
        </div>
    </div>
</div>