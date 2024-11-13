<style>
    .sortable-main {
        background-color: var(--c-surface-a80);
    }

    .sortable-main .sortable-item {
        /* background-color: white; */
        border: 1px solid rgba(0, 0, 0, 0.1);
        min-width: calc(100% / 4);
        max-width: calc(100% / 4);
        min-height: auto;
        aspect-ratio: 1 / 1;
    }

    .sortable-main .sortable-item .sortable-img {
        /* min-width: 100%;
        min-height: 140px; */
        aspect-ratio: 1 / 1;

        position: relative;
        box-sizing: border-box;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sortable-main .sortable-item .sortable-img img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sortable-main .sortable-item .image-preview {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        background-color: rgba(0, 0, 0, 0.25);
    }

    /* Image fullscreen preview */
    .sortable-main .sortable-item .image-preview .bi {
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3rem;
    }

    .sortable-main .sortable-item .image-preview {
        cursor: zoom-in;
        transition: 0.1s ease;
        opacity: 0%;
    }

    .sortable-main .sortable-item .image-preview:hover {
        opacity: 100%;
    }
</style>


<div id="sortableCarousel" class="sortable-main bg-light-3 border">
    <div class="d-flex flex-wrap gap-0 p-1" role="listbox" id="sortableContentHomeCarousel">
        <?php
        if (!empty($images)) {
            foreach ($images as $image) {
                // Create a base64 string from the binary data in the image column
                $imageData = BASE_PATH_LINK . htmlspecialchars($image['image_path']);


                $imageTitle = htmlspecialchars($image['title']);
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
                                <button class="btn-remove btn border-0 bg-transparent text-danger" title="Remove">
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
        ?>
    </div>


</div>

<?php include_once "../../../../src/views/partials/public/modal_formFileSelect.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>


<script>
    var sortable = Sortable.create(document.querySelector('#sortableContentHomeCarousel'), {
        group: 'home-carousel',
        animation: 150,
        handle: '.dragger-handle',
        direction: 'horizontal', // Ensure horizontal dragging
        filter: '.ignore-drag',
        onEnd: function (evt) {
            console.log("Element moved from position", evt.oldIndex, "to", evt.newIndex);
        }
    });

    document.querySelectorAll('.btn-remove').forEach(function (button) {
        button.addEventListener('click', function () {
            if (confirm('Are you sure you want to remove this item?')) {
                this.closest('.sortable-item').remove();
            }
        });
    });
</script>