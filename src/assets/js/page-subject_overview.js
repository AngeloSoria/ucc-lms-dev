$(document).ready(function () {
    $("#confirmDeleteBtn").on('click', function () {
        $("#inputAction").val("deleteSubjectModule");
        $("#formModuleInformation").submit();
    });
});

// For Module Content's controls.
$(document).ready(function () {
    // $("#toggleFileInput").on("click", function () {
    //     const isChecked = $("#toggleFileInput").prop("checked");

    //     // Toggle visibility and disabled state
    //     if (isChecked) {
    //         $("#fileInput").removeClass("d-none").prop("disabled", false);
    //     } else {
    //         $("#fileInput").addClass("d-none").prop("disabled", true);
    //     }
    // });

    $(".contentButton_ToggleVisibity").on("click", function (e) {
        $.ajax({
            url: "",
            type: "POST",
            data: {
                action: "updateModuleContentVisibility",
                content_id: $(this).attr("id"),
            }, // Data to send to the server
            success: function (response) {
                // console.log(response); // Handle success
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error); // Handle error
            }
        });
    });
    $(".contentButton_Delete").on("click", function () {
        const this_content_id = $(this).attr("id");
        $("#confirmDeleteContentBtn").on("click", function () {
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    action: "deleteModuleContent",
                    content_id: this_content_id,
                }, // Data to send to the server
                success: function (response) {
                    // console.log(response); // Handle success
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(error); // Handle error
                }
            });
        });
    });
});


$(document).ready(function () {
    // Function to toggle fields based on Content Type selection
    function toggleFields() {
        const selectedType = $('#contentType').val();

        // $("#fileInput").addClass("d-none").prop("disabled", true);

        // Show/hide fields and set required attributes
        if (selectedType === 'information') {
            $('#descriptionContainer').removeClass('d-none').find('textarea').attr('required', false);
            $('#fileInputContainer').removeClass('d-none').find('input[type="file"]').attr('required', true);
            $('#visibilityContainer').removeClass('d-none');

            $('#dateContainer').addClass('d-none');
            $('#maxAttemptsContainer').addClass('d-none');
            $('#assignmentTypeContainer').addClass('d-none');
            $('#allowLateContainer').addClass('d-none');
            $('#maxScoreContainer').addClass('d-none'); // Hide for handout

        } else if (selectedType === 'handout') {
            $('#descriptionContainer').removeClass('d-none').find('textarea').attr('required', false);
            $('#fileInputContainer').removeClass('d-none').find('input[type="file"]').attr('required', true);
            $('#visibilityContainer').removeClass('d-none');
            $('#dateContainer').addClass('d-none');
            $('#maxAttemptsContainer').addClass('d-none');
            $('#assignmentTypeContainer').addClass('d-none');
            $('#allowLateContainer').addClass('d-none');
            $('#maxScoreContainer').addClass('d-none'); // Hide for handout
        } else if (selectedType === 'assignment') {
            $('#descriptionContainer').removeClass('d-none').find('textarea').attr('required', false);
            $('#dateContainer').removeClass('d-none').find('input').attr('required', true);
            $('#maxAttemptsContainer').removeClass('d-none').find('input[type="number"]').attr('required', true);
            $('#assignmentTypeContainer').removeClass('d-none').find('select').attr('required', true);
            $('#allowLateContainer').removeClass('d-none');
            $('#visibilityContainer').removeClass('d-none');
            $('#maxScoreContainer').removeClass('d-none').find('input').attr('required', true); // Show for assignment

            $('#fileInputContainer').removeClass('d-none').find('input[type="file"]').attr('required', true);

            // $('#fileInputContainer').addClass('d-none');
        } else {
            $('#descriptionContainer').addClass('d-none').find('textarea').attr('required', false);
            $('#dateContainer').removeClass('d-none').find('input').attr('required', true);
            $('#visibilityContainer').removeClass('d-none');
            $('#maxScoreContainer').addClass('d-none'); // Hide for other types
            $('#allowLateContainer').removeClass('d-none');
            $('#maxAttemptsContainer').removeClass('d-none');

            $('#fileInputContainer').addClass('d-none').find('input[type="file"]').attr('required', false);
            $('#assignmentTypeContainer').addClass('d-none');
        }
    }

    // Function to validate Start Date and Due Date
    function validateDates() {
        if ($('#startDate').prop('disabled') || $('#dueDate').prop('disabled')) {
            return true;
        }
        const now = new Date();
        const startDate = new Date($('#startDate').val());
        const dueDate = new Date($('#dueDate').val());
        let isValid = true;

        // Start Date Validation
        if (startDate < now) {
            $('#startDate').addClass('is-invalid').siblings('.invalid-feedback').text('Start Date must be today or later.');
            isValid = false;
        } else {
            $('#startDate').removeClass('is-invalid');
        }

        // Due Date Validation
        if (dueDate <= startDate) {
            $('#dueDate').addClass('is-invalid').siblings('.invalid-feedback').text('Due Date must be after Start Date and time.');
            isValid = false;
        } else {
            $('#dueDate').removeClass('is-invalid');
        }

        return isValid;
    }

    // Function to validate required fields
    function validateRequiredFields() {
        let isValid = true;

        // Validate inputs, selects, and textareas with the 'required' attribute
        $('#moduleContentForm input[required], #moduleContentForm select[required], #moduleContentForm textarea[required]').each(function () {

            // Skip disabled or hidden fields (e.g., file input)
            if ($(this).prop('disabled') || $(this).closest('div').hasClass('d-none')) {
                return; // Skip this field
            }

            // If it's a checkbox, validate its checked state
            if ($(this).is(':checkbox') && !$(this).prop('checked')) {
                $(this).addClass('is-invalid').siblings('.invalid-feedback').text('This field is required.');
                isValid = false;
            }
            // For other fields, check if they have a value
            else if (!$(this).val()) {
                $(this).addClass('is-invalid').siblings('.invalid-feedback').text('This field is required.');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        return isValid;
    }




    // Bind to Content Type change event
    $('#contentType').on('change', toggleFields);

    // Validate dates and required fields before form submission
    $('#moduleContentForm').on('submit', function (e) {
        const isDatesValid = validateDates();
        const areFieldsValid = validateRequiredFields();

        if (!isDatesValid || !areFieldsValid) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // Trigger the change event on modal open to ensure correct state
    $('#addModuleContentModal').on('show.bs.modal', function () {
        $('#contentType').trigger('change');
    });
});