<style>
    .sortable-main {
        /* background-color: lightblue; */
    }

    .sortable-main .sortable-item {
        /* background-color: white; */
    }

    .sortable-main .sortable-item .sortable-img {
        min-width: 300px;
        min-height: 250px;

        max-width: 300px;
        max-height: 250px;

        position: relative;
        box-sizing: border-box;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .sortable-main .sortable-item .sortable-img img {
        position: absolute;
        width: 95%;
        height: 95%;
        object-fit: cover;
    }
</style>


<div id="sortableCarousel" class="sortable-main bg-light-3 border d-flex align-items-center flex-wrap">
    <div class="d-flex flex-wrap gap-2 p-2" role="listbox" id="sortableContentHomeCarousel">

        <div class="sortable-item bg-white shadow-sm rounded">
            <div class="top position-relative p-1">
                <div class="sortable-img p-1">
                    <!-- <img src="https://via.placeholder.com/800x500" class="d-block"> -->
                    <img src="<?php echo $_ROOT_PATH ?>/src/assets/images/placeholder-1.jpg" class="d-block">
                </div>
                <div class="z-1 w-100 d-flex justify-content-end align-items-center">
                    <button class="btn-remove btn border-0 bg-transparent text-danger" title="Remove">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                    <button class="btn-preview btn border-0 bg-transparent" title="Preview">
                        <i class="bi bi-arrows-fullscreen fs-6"></i>
                    </button>
                    <button class="dragger-handle btn border-0 bg-transparent" title="Drag to move">
                        <i class="bi bi-grip-vertical fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="sortable-item bg-white shadow-sm rounded">
            <div class="top position-relative p-1">
                <div class="sortable-img p-1">
                    <!-- <img src="https://via.placeholder.com/800x500" class="d-block"> -->
                    <img src="<?php echo $_ROOT_PATH ?>/src/assets/images/placeholder-2.jpg" class="d-block">
                </div>
                <div class="z-1 w-100 d-flex justify-content-end align-items-center">
                    <button class="btn-remove btn border-0 bg-transparent text-danger" title="Remove">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                    <button class="btn-preview btn border-0 bg-transparent" title="Preview">
                        <i class="bi bi-arrows-fullscreen fs-6"></i>
                    </button>
                    <button class="dragger-handle btn border-0 bg-transparent" title="Drag to move">
                        <i class="bi bi-grip-vertical fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="sortable-item bg-white shadow-sm rounded">
            <div class="top position-relative p-1">
                <div class="sortable-img p-1">
                    <!-- <img src="https://via.placeholder.com/800x500" class="d-block"> -->
                    <img src="<?php echo $_ROOT_PATH ?>/src/assets/images/placeholder-3.jpg" class="d-block">
                </div>
                <div class="z-1 w-100 d-flex justify-content-end align-items-center">
                    <button class="btn-remove btn border-0 bg-transparent text-danger" title="Remove">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                    <button class="btn-preview btn border-0 bg-transparent" title="Preview">
                        <i class="bi bi-arrows-fullscreen fs-6"></i>
                    </button>
                    <button class="dragger-handle btn border-0 bg-transparent" title="Drag to move">
                        <i class="bi bi-grip-vertical fs-5"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- Add New Button -->
    <div
        class="ignore-drag border rounded bg-white flex-grow-0 px-4"
        id="btnAddNewImage"
        role="button"
        data-bs-toggle="modal"
        data-bs-target="#fileSelectModal">
        <div class="top position-relative d-flex flex-column justify-content-center align-items-center" style="height: 120px;">
            <h6 class="ctxt-primary">Add New</h6>
            <input type="file" name="home_carousel_uplpad" id="home_carousel_uplpad" class="d-none">
            <div class="border-0 bg-transparent py-0">
                <i class="bi bi-plus-circle fs-3 ctxt-primary"></i>
            </div>
        </div>
    </div>
</div>
<?php include_once "../../../../src/views/partials/public/modal_formFileSelect.php" ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
    // There should be an indicator for max upload image.
    var sortable = Sortable.create(document.querySelector('#sortableContentHomeCarousel'), {
        group: 'home-carousel',
        animation: 150,
        handle: '.dragger-handle',
        direction: 'horizontal', // Ensure horizontal dragging
        filter: '.ignore-drag',
        onEnd: function(evt) {
            // Optionally, handle the event after reordering
            console.log("Element moved from position", evt.oldIndex, "to", evt.newIndex);
        }
    });

    document.querySelectorAll('.btn-remove').forEach(function(button) {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                this.closest('.sortable-item').remove();
            }
        });
    });
</script>