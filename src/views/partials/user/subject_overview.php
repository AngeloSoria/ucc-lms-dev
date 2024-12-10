<div class="d-flex flex-column gap-2 flex-grow-1">
    <div class="bg-white shadow-sm rounded px-2 pt-3">
        <div id="top-controls" class="row m-0">
            <div class="col-lg-8">
                <h5 class="text-success">
                    <?php echo $SUBJECT_INFO['data']['subject_name'] . ' (' . $SECTION_INFO['data']['section_name'] . ')' ?>
                </h5>
            </div>
            <div class="col-lg-4 d-flex justify-content-end align-items-center">
                <?php if (userHasPerms(["Teacher", "Admin"]) && !isset($_GET['module_id']) && !isset($_GET['assignments'])): ?>
                    <button class="btn btn-success shadow-sm d-flex gap-2" data-bs-toggle="modal"
                        data-bs-target="#modal_addModule">
                        <i class="bi bi-plus-circle"></i>
                        Add Module
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <?php if (isset($_GET['module_id'], $_GET['content_id'], $_GET['students_submission'])): ?>
            <section id="modules_container" class="d-flex flex-column gap-3 mb-3">
                <?php require_once PARTIALS . 'user/partial_subject-overview-moduleContent-studentsubmission.php' ?>
            </section>
        <?php elseif (isset($_GET['assignments'])): ?>
            <section id="modules_container" class="d-flex flex-column gap-3 mb-3">
                <?php require_once PARTIALS . 'user/partial_subject-overview_assignments.php' ?>
            </section>
        <?php elseif (!isset($_GET['module_id'])): ?>
            <section id="modules_container" class="d-flex flex-column gap-3 mb-3">
                <?php
                // Load All Modules from this Subject
                $getAllModules = $moduleContentController->getModules($_GET['subject_section_id']);
                if ($getAllModules['success']): ?>
                    <?php if ($getAllModules['data']): ?>
                        <?php $noModulesShown = true; ?>
                        <?php foreach ($getAllModules['data'] as $module): ?>
                            <?php if ($module['visibility'] == "shown" || userHasPerms(["Teacher", "Admin"])): ?>
                                <?php $noModulesShown = false; ?>

                                <div class="accordion rounded shadow-sm border overflow-hidden"
                                    id="module_<?php echo $module['module_id'] ?>">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule<?php echo $module['module_id'] ?>"
                                                aria-expanded="false"
                                                aria-controls="collapseModule<?php echo $module['module_id'] ?>">
                                                <?php echo htmlspecialchars($module['title']) ?>
                                            </button>
                                        </h2>
                                        <div id="collapseModule<?php echo $module['module_id'] ?>"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingModule<?php echo $module['module_id'] ?>"
                                            data-bs-parent="#module_<?php echo $module['module_id'] ?>">
                                            <div class="accordion-body">
                                                <?php if (userHasPerms(["Teacher", "Admin"])): ?>
                                                    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                        <a
                                                            href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $module['module_id']]) ?>">
                                                            <button class="btn btn-sm btn-primary shadow-sm text-white"
                                                                title="Edit this Module">
                                                                <i class="bi bi-pencil-square"></i>
                                                                Edit Module
                                                            </button>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php
                                                    // Get All contents of the module from this Subject.
                                                    $getAllContentsFromModule = $moduleContentController->getContents($module['module_id']);

                                                    if ($getAllContentsFromModule['success']): ?>
                                                        <?php if ($getAllContentsFromModule['data']): ?>
                                                            <?php foreach ($getAllContentsFromModule['data'] as $content):
                                                                $canShow = (userHasPerms(["Teacher", "Admin"]) || userHasPerms(["Student"]) && $content['visibility'] == 'shown');
                                                                if ($canShow):
                                                            ?>
                                                                    <li class="list-group-item d-flex gap-2">
                                                                        <?php
                                                                        $contentLink = updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $module['module_id'], 'content_id' => $content['content_id']]);
                                                                        $isFile = false;
                                                                        $contentIconClass = "bi-asterisk text-dark";
                                                                        $titleHint = "null";
                                                                        switch ($content['content_type']) {
                                                                            case 'handout':
                                                                                $contentIconClass = "bi-file-earmark-text-fill text-critical";
                                                                                $titleHint = "Handout";
                                                                                // $contentLink = updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $module['module_id'], 'content_id' => $content['content_id']]);
                                                                                // $contentLink = BASE_PATH_LINK . "src/models/DownloadFile.php?content_id=" . $content['content_id'];
                                                                                break;
                                                                            case 'assignment':
                                                                                $contentIconClass = "bi-clipboard-fill text-primary";
                                                                                $titleHint = "Assignment";
                                                                                break;
                                                                            case 'information':
                                                                                $contentIconClass = getBootstrapIcon('content');
                                                                                $titleHint = "Information";
                                                                                break;
                                                                            case 'quiz':
                                                                                $contentIconClass = "bi-stickies-fill text-warning";
                                                                                $titleHint = "Quiz";
                                                                                break;
                                                                        }

                                                                        ?>
                                                                        <div>
                                                                            <i class="bi <?php echo $contentIconClass ?>" title="<?php echo $titleHint ?>"></i>
                                                                            <a href="<?php echo $contentLink ?>" class="link-body-emphasis"><?php echo htmlspecialchars($content['content_title']) ?></a>
                                                                        </div>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <h6 class="p-2 text-center">No Contents</h6>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </ul>
                                                <?php if (userHasPerms(["Teacher", "Admin"])): ?>
                                                    <div class="p-3 text-end border-top">
                                                        <span>Visible to students:
                                                            <strong><?php echo $module['visibility'] == 'shown' ? "Yes" : "No"; ?></strong></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>
                        <?php if ($noModulesShown): ?>
                            <h6 class="text-center">No modules available</h6>
                        <?php endif; ?>
                    <?php else: ?>
                        <h6 class="text-center">No modules shown.</h6>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        <?php elseif (isset($_GET['module_id'], $_GET['content_id'])): ?>
            <?php
            require_once PARTIALS . 'user/partial_subject-overview_module&content.php';
            ?>
        <?php else: ?>
            <?php
            if (!userHasPerms(['Teacher', 'Admin'])) {
                echo '<script>window.location = \'' . BASE_PATH_LINK . '\'</script>';
                exit;
            }
            ?>
            <section id="module_editor_view" class="p-2 mb-3">
                <div>
                    <a href="<?php echo updateUrlParams(["subject_section_id" => $_GET['subject_section_id']]) ?>">
                        <button class="btn btn-sm btn-transparent text-success">
                            <i class="bi bi-arrow-bar-left"></i>
                            Go Back
                        </button>
                    </a>
                </div>
                <div class="fs-5 text-center mb-3">Module Information</div>
                <form id="formModuleInformation" method="POST">
                    <input type="hidden" name="action" value="updateSubjectModule" id="inputAction">
                    <div class="row">
                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <label for="input_moduleName">Module Name</label>
                                <input type="text" name="input_moduleName" id="input_moduleName"
                                    class="form-control"
                                    value="<?php echo $getModuleByModuleId['data'][0]['title'] ?>">
                            </div>
                            <div class="col-md-6 mt-sm-4 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="input_moduleVisibility" name="input_moduleVisibility" <?php echo $getModuleByModuleId['data'][0]['visibility'] == 'shown' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="input_moduleVisibility">
                                        Module Visibility
                                        <sup class="opacity-75"
                                            title="Indicates the visibity of the module to Student's point of view.">
                                            <i class="bi bi-info-circle-fill fs-7"></i>
                                        </sup>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-start px-3 mt-5 gap-2">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-floppy-fill"></i>
                                Save
                            </button>
                            <span class="btn btn-sm btn-danger" id="btnDelete" data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmationModal">
                                <i class="bi bi-trash-fill"></i>
                                Delete Module
                            </span>
                        </div>
                    </div>
                </form>
                <section class="mt-5">
                    <div class="row mb-2">
                        <div class="col-md-6">Contents</div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <button class="btn btn-sm btn-success d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addModuleContentModal">
                                <i class="bi bi-plus-circle"></i>
                                Add Content
                            </button>
                        </div>
                    </div>
                    <?php require_once PARTIALS . 'user/partial_subjectModuleContents.php' ?>
                </section>
            </section>
        <?php endif; ?>
    </div>
</div>

<?php if (userHasPerms(["Teacher", "Admin"])): ?>

    <!-- SUBJECT MODULES -->
    <?php include_once PARTIALS . 'user/modal_addSubjectModule.php' ?>
    <?php include_once PARTIALS . 'user/modal_promptConfirm_deleteSubjectModule.php' ?>

    <!-- MODULE CONTENTS -->
    <?php include_once PARTIALS . 'user/modal_addModuleContent.php' ?>
    <?php include_once PARTIALS . 'user/modal_promptConfirm_deleteModuleContent.php' ?>

    <!-- Script Dependencies -->
    <script src="<?php echo asset('js/page-subject_overview.js')  ?>"></script>

<?php endif; ?>