$(document).ready(function () {
    let previewTypeContainer = $('#previewTypeContainer');

    // Loop through elements with 'preview-container-name'
    $('[preview-container-name]').each(function () {
        // Check if the element has the 'preview-container-default' attribute
        if ($(this).attr('preview-container-default')) {
            // Get the corresponding button with 'preview-container-target' matching 'preview-container-name'
            let containerName = $(this).attr('preview-container-name');

            $('[preview-container-target="' + containerName + '"]').each(function () {
                if ($(this).is('button')) {
                    // Add 'btn-outline-primary' class to the button corresponding to the default container
                    $(this).addClass('btn-outline-primary');
                }
            });
        }
    });

    // Apply 'btn-outline-primary' class to all buttons within the previewTypeContainer
    previewTypeContainer.find('button').each(function () {
        $(this).addClass('btn-outline-primary');
    });

    // Add a click listener to preview-container-target elements
    $('[preview-container-target]').on('click', function () {
        // Remove 'btn-primary' class from all buttons and add 'btn-outline-primary'
        $('[preview-container-target]').removeClass('btn-primary').addClass('btn-outline-primary');

        // Add 'btn-primary' to the clicked button to highlight it
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');

        // Get the target container name from the clicked button's 'preview-container-target' attribute
        let targetContainerName = $(this).attr('preview-container-target');

        // Loop through all preview containers
        $('[preview-container-name]').each(function () {
            // Check if the current container's name matches the target
            if ($(this).attr('preview-container-name') === targetContainerName) {
                // Show the matching preview container by removing the 'd-none' class
                $(this).removeClass('d-none');
            } else {
                // Hide other containers by adding the 'd-none' class
                $(this).addClass('d-none');
            }
        });
    });
});