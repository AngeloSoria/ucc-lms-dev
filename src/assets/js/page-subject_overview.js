$(document).ready(function () {
    // Constants
    const $contentType = $('#contentType');
    const $moduleContentForm = $('#moduleContentForm');

    // Utility Functions
    function toggleFields() {
        const selectedType = $contentType.val();
        const config = {
            information: {
                show: ['#descriptionContainer', '#fileInputContainer', '#visibilityContainer'],
                hide: ['#dateContainer', '#maxAttemptsContainer', '#assignmentTypeContainer', '#allowLateContainer', '#maxScoreContainer'],
                fileRequired: false,
            },
            handout: {
                show: ['#descriptionContainer', '#visibilityContainer', '#fileInputContainer'],
                hide: ['#dateContainer', '#maxAttemptsContainer', '#assignmentTypeContainer', '#allowLateContainer', '#maxScoreContainer'],
                fileRequired: true,
            },
            assignment: {
                show: ['#descriptionContainer', '#dateContainer', '#maxAttemptsContainer', '#assignmentTypeContainer', '#allowLateContainer', '#visibilityContainer', '#maxScoreContainer', '#fileInputContainer'],
                hide: [],
                fileRequired: false,
            },
        }[selectedType] || {
            show: ['#dateContainer', '#visibilityContainer', '#maxAttemptsContainer', '#allowLateContainer', '#descriptionContainer'],
            hide: ['#fileInputContainer', '#assignmentTypeContainer', '#maxScoreContainer'],
            fileRequired: false,
        };

        $(config.show.join(',')).removeClass('d-none');
        $(config.hide.join(',')).addClass('d-none');
        $('#fileInputContainer input[type="file"]').attr('required', config.fileRequired);
    }

    function validateDates() {
        if ($('#startDate').prop('disabled') || $('#dueDate').prop('disabled')) return true;
        const now = new Date();
        const startDate = new Date($('#startDate').val());
        const dueDate = new Date($('#dueDate').val());
        let isValid = true;

        // Validate Start Date
        if (startDate < now) {
            $('#startDate').addClass('is-invalid').siblings('.invalid-feedback').text('Start Date must be today or later.');
            isValid = false;
        } else {
            $('#startDate').removeClass('is-invalid');
        }

        // Validate Due Date
        if (dueDate <= startDate) {
            $('#dueDate').addClass('is-invalid').siblings('.invalid-feedback').text('Due Date must be after Start Date and time.');
            isValid = false;
        } else {
            $('#dueDate').removeClass('is-invalid');
        }

        return isValid;
    }

    function validateRequiredFields() {
        let isValid = true;
        $moduleContentForm.find('input[required], select[required], textarea[required]').each(function () {
            if ($(this).prop('disabled') || $(this).closest('div').hasClass('d-none')) return;
            if ($(this).is(':checkbox') && !$(this).prop('checked') || !$(this).val()) {
                $(this).addClass('is-invalid').siblings('.invalid-feedback').text('This field is required.');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    }

    // Event Listeners
    $contentType.on('change', toggleFields);

    $moduleContentForm.on('submit', function (e) {
        if (!validateDates() || !validateRequiredFields()) e.preventDefault();
    });

    $('#addModuleContentModal').on('show.bs.modal', function () {
        $contentType.trigger('change');
    });

    // Ajax Handlers
    $("#confirmDeleteBtn").on('click', function () {
        $("#inputAction").val("deleteSubjectModule");
        $("#formModuleInformation").submit();
    });

    $(".contentButton_ToggleVisibity").on("click", function () {
        const contentId = $(this).attr("id");
        $.post("", { action: "updateModuleContentVisibility", content_id: contentId }, function () {
            location.reload();
        }).fail(function (xhr, status, error) {
            console.error(error);
        });
    });

    $(".contentButton_Delete").on("click", function () {
        const contentId = $(this).attr("id");
        $("#confirmDeleteContentBtn").off('click').on("click", function () {
            $.post("", { action: "deleteModuleContent", content_id: contentId }, function () {
                location.reload();
            }).fail(function (xhr, status, error) {
                console.error(error);
            });
        });
    });

    $("#toggleFileInput").on("click", function () {
        const $fileInput = $("#fileInput");
        const isChecked = $(this).prop("checked");
        $fileInput.toggleClass("d-none", !isChecked).prop("disabled", !isChecked);
    });
});
