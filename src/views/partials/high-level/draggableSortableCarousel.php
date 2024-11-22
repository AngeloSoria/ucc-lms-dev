<?php

function createSortable($images)
{
    if (!empty($images)) {
        foreach ($images as $image) {
            // Create a base64 string from the binary data in the image column
            $imageData = BASE_PATH_LINK . htmlspecialchars($image['image_path']);

            $imageTitle = htmlspecialchars($image['title']);
            $imageId = htmlspecialchars($image['carousel_id']);
            echo <<<HTML
                <div class="sortable-item bg-white shadow-sm rounded">
                    <div class="top position-relative p-1">
                        <div class="sortable-img p-1">
                            
                            <div class="image-preview" title="Preview" onclick="openPreview('$imageData');">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </div>

                            <img src="$imageData" alt="$imageTitle" class="d-block">
                        </div>

                        <!-- controls -->
                        <div class="sortable-controls z-1 w-100 d-flex justify-content-end align-items-center">
                            <button id="$imageId" class="btn-remove btn border-0 bg-transparent text-danger" title="Remove">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                            <button class="dragger-handle btn border-0 bg-transparent" title="Drag to move">
                                <i class="bi bi-grip-vertical fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            HTML;
        }
    }
}
