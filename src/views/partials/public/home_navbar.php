<nav class="shadow-sm z-1 p-2" id="topNav">
    <div class="nav-container bg-transparent m-auto px-5 w-75 d-flex justify-content-between">
        <!-- Logo on the left -->
        <a class="navbar-brand" href="<?php echo BASE_PATH_LINK; ?>"> <!-- This will take you to the base URL -->
            <img src="<?php echo asset('img/ucc-logo.png') ?>" alt="Logo" width="110"
                class="d-inline-block align-text-top" />
        </a>

        <!-- Small screen button -->
        <button class="border-0 bg-transparent fs-3" id="btnOpenNavSideBarMobile">
            <i class="bi bi-list"></i>
        </button>

        <!-- Navbar links and button on the right -->
        <div class="nav-subcontent justify-content-end d-flex gap-5" id="navbarNav">
            <ul class="navbar-nav d-flex gap-5">
                <li class="nav-item">
                    <a class="nav-link" href="faq">FAQ</a>
                </li>
            </ul>

            <!-- LOGIN button -->
            <button class="btn btn-primary ms-2 btn-lg px-4 rounded-pill fs-6 custom_btn_login" type="button"
                data-bs-toggle="modal" data-bs-target="#modal_LoginForm">
                Log in
            </button>
        </div>
    </div>
</nav>

<!-- Small screen sidebar popup -->
<div class="nav-sidebar bg-light shadow overflow-x-hidden" id="navSideBar">
    <div class="float-end bg-transparent p-4">
        <button class="fs-3 border-0 bg-transparent text-dark" id="btnCloseNavSideBarMobile">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="bg-light w-100 px-3 d-flex flex-column align-items-center justify-content-center gap-2 mt-3">
        <img width="200" src="<?php echo asset('img/ucc-logo.png') ?>" alt="ucc logo">
        <p class="fs-5 text-center mt-3">Learning Management System</p>
    </div>
    <hr>
    <br>
    <br>
    <div class="w-100 px-3 bg-transparent d-flex justify-content-center align-items-center flex-column py-2 gap-3 mb-3">
        <button class="w-100 border-0 p-2 fs-2 btn btn-primary btn-lg custom_btn_login" type="button"
            data-bs-toggle="modal" data-bs-target="#modal_LoginForm">
            Login
        </button>
        <a href="faq" class="w-100 rounded border p-2 fs-2">
            <button class="border-0 bg-transparent w-100">FAQ</button>
        </a>
    </div>
</div>