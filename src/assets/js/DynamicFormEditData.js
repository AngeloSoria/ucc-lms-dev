const dynamic_form_ids = {
    "edit_Section": {
        "allowedInputNames": [],
        "initFunc": function (element) {
            let input_sectionEducationalLevel = $(element).find('#input_sectionEducationalLevel')[0];
            let input_sectionPrograms = $(element).find("#input_sectionPrograms")[0];
            let input_sectionYearLevel = $(element).find("#input_sectionYearLevel")[0];
            let input_sectionAdviser = $(element).find("#input_sectionAdviser")[0];

            $(input_sectionEducationalLevel).on('change', function () {
                // Year level
                $(input_sectionYearLevel).empty();
                if ($(input_sectionEducationalLevel).val() === "College") {
                    $(input_sectionYearLevel).append('<option value="1">1</option>');
                    $(input_sectionYearLevel).append('<option value="2">2</option>');
                    $(input_sectionYearLevel).append('<option value="3">3</option>');
                    $(input_sectionYearLevel).append('<option value="4">4</option>');
                } else if ($(input_sectionEducationalLevel).val() === "SHS") {
                    $(input_sectionYearLevel).append('<option value="11">11</option>');
                    $(input_sectionYearLevel).append('<option value="12">12</option>');
                }

                // Select2 Adviser
                $(input_sectionAdviser).select2({
                    placeholder: "Search and select a teacher",
                    allowClear: true,
                    ajax: {
                        url: "",
                        type: "POST",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                search_type: "teacher",
                                query: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(teacher => ({
                                    id: teacher.user_id,
                                    text: `${teacher.name} (${teacher.user_id})`
                                }))
                            };
                        }
                    }
                });

                // Select options onchange.
                $.ajax({
                    url: '../../../views/partials/high-level/_rFetch_programs.php',
                    method: 'POST',
                    data: {
                        educational_level: $(input_sectionEducationalLevel).val()
                    },
                    success: function (response) {
                        $(input_sectionPrograms).empty();
                        response.data.forEach(item => {
                            console.log(item.program_name);
                            $(input_sectionPrograms).append(`<option value="${item.program_id}">${item.program_code} | ${item.program_name}</option>`);
                        });
                    },
                    error: function (xhr, status, error) {
                        let response = null;
                        try {
                            response = JSON.parse(xhr.responseText);
                            if (!response.success) {
                                makeToast("error", response.message);
                            }
                        } catch (e) {
                            console.error("Failed to parse response:", xhr.responseText);
                        }
                    }
                })
            })
        },
        "AJAX_Save": function (target_section_id) {

        }
    },
};

$(document).ready(function () {
    let BTN_EDIT = $("#dynamic_btn_edit");
    let BTN_SAVE = $("#dynamic_btn_save");
    let BTN_CANCEL = $("#dynamic_btn_cancel");

    let DYNAMIC_FORM = $('[dynamic-form-id]');
    console.log(DYNAMIC_FORM[0]);
    if (DYNAMIC_FORM[0] !== undefined) {
        dynamic_form_ids[DYNAMIC_FORM.attr("dynamic-form-id")].initFunc(DYNAMIC_FORM[0]);
    }

    // Array to store original values of form elements
    let originalValues = [];

    function init() {
        BTN_EDIT.addClass('d-block');
        BTN_SAVE.addClass('d-none');
        BTN_CANCEL.addClass('d-none');
        setStateUpdate(false); // Ensure form elements are disabled initially
        console.log("==[Dynamic Form Edit Data v3 loaded.]==");
    }

    function setStateUpdate(state) {
        if ($.type(state) != 'boolean') return;

        $('[update-enabled]').each(function () {
            if ($(this).is('input, textarea, select')) {
                $(this).prop('disabled', !state);
            }
        });
    }

    BTN_EDIT.on('click', function () {
        BTN_EDIT.removeClass('d-block').addClass('d-none');
        BTN_SAVE.removeClass('d-none').addClass('d-block');
        BTN_CANCEL.removeClass('d-none').addClass('d-block');

        // Store the current values of all relevant form elements with `update-enabled`
        originalValues = [];
        $('[update-enabled]').each(function () {
            const element = $(this);
            if (element.is('input, textarea, select')) {
                originalValues.push({
                    element: element,
                    value: element.val(), // Store current value
                    defaultOption: element.is('select') ? element.find(':selected').val() : null // Store default for selects
                });
            }
        });

        setStateUpdate(true);
    });

    BTN_CANCEL.on('click', function () {
        let makeUpdate = confirm('Are you sure you do not want to update this information?');
        if (makeUpdate) {
            BTN_CANCEL.removeClass('d-block').addClass('d-none');
            BTN_SAVE.removeClass('d-block').addClass('d-none');
            BTN_EDIT.removeClass('d-none').addClass('d-block');

            // Revert all form elements to their original values
            originalValues.forEach(({ element, value }) => {
                if (element.is('select')) {
                    // Revert select to its original option
                    element.val(value);
                } else {
                    // Revert other inputs to their original value
                    element.val(value);
                }
            });

            setStateUpdate(false);
        }
    });

    init();

    let isFormDirty = false;

    // Example: Detect changes in a form
    document.querySelector('form').addEventListener('change', function () {
        isFormDirty = true;
    });

    window.addEventListener('beforeunload', function (event) {
        if (isFormDirty) {
            event.preventDefault();
            // event.returnValue = ''; // Modern browsers require this to show a dialog
        }
    });
});
