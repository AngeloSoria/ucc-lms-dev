<section id="module_contents" class="border">
    <ul class="list-group list-group-flush overflow-hidden">
        <?php
        $getAllContentsFromModule = $moduleContentController->getContents($getModuleByModuleId['data'][0]['module_id']);
        if ($getAllContentsFromModule['success']):
            if ($getAllContentsFromModule['data']):
                foreach ($getAllContentsFromModule['data'] as $content):
                    $contentIconClass = "bi-asterisk text-dark";
                    $titleHint = "null";
                    $contentVisibilityIcon = $content['visibility'] == "shown" ? "bi-eye-fill" : "bi-eye-slash-fill";
                    switch ($content['content_type']) {
                        case 'handout':
                            $contentIconClass = "bi-file-earmark-text-fill text-critical";
                            $titleHint = "Handout";
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
                    <li class="list-group-item row d-flex align-items-center">
                        <div class="col-sm-8">
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <a href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $_GET['module_id'], 'content_id' => $content['content_id']]) ?>">
                                    <i class="bi <?php echo $contentIconClass ?>" title="<?php echo $titleHint ?>"></i>
                                    <span class="link-body-emphasis"><?php echo htmlspecialchars($content['content_title']) ?></span>
                                </a>
                            </div>
                        </div>
                        <div
                            class="col-sm-4 d-flex justify-content-end align-items-center gap-2">
                            <button id="<?php echo $content['content_id'] ?>"
                                class="contentButton_ToggleVisibity btn btn-sm btn-transparent text-dark"
                                title="visibility">
                                <i class="fs-6 bi <?php echo $contentVisibilityIcon ?>"></i>
                            </button>
                            <button id="<?php echo $content['content_id'] ?>"
                                class="contentButton_Delete btn btn-sm btn-transparent text-danger"
                                title="delete" data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmationModalForContent">
                                <i class="fs-6 bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li
                    class="list-group-item row d-flex align-items-center justify-content-center">
                    No contents...
                </li>
            <?php endif; ?>
        <?php else: ?>
            <li
                class="list-group-item row d-flex align-items-center justify-content-center text-danger">
                Something went wrong when getting modules. (please contact the administrator)
            </li>
        <?php endif; ?>
    </ul>
</section>