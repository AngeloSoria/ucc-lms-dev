const dynamic_form_ids = {
    "edit_Section": {
        "allowedInputNames": [],
        "initFunc": function (element) {
            let $input_sectionEducationalLevel = $(element).find("#input_sectionEducationalLevel")[0];
            let $input_sectionPrograms = $(element).find("#input_sectionPrograms")[0];
            let $input_sectionYearLevel = $(element).find("#input_sectionYearLevel")[0];
            let $input_sectionAdviser = $(element).find("#input_sectionAdviser")[0];

            $($input_sectionEducationalLevel).on("change", function () {
                // Year level
                $($input_sectionYearLevel).empty();
                if ($($input_sectionEducationalLevel).val() === "College") {
                    $($input_sectionYearLevel).append('<option value="1">1</option>');
                    $($input_sectionYearLevel).append('<option value="2">2</option>');
                    $($input_sectionYearLevel).append('<option value="3">3</option>');
                    $($input_sectionYearLevel).append('<option value="4">4</option>');
                } else if ($($input_sectionEducationalLevel).val() === "SHS") {
                    $($input_sectionYearLevel).append('<option value="11">11</option>');
                    $($input_sectionYearLevel).append('<option value="12">12</option>');
                }

                // Select options onchange.
                $.ajax({
                    url: "../../../views/partials/high-level/_rFetch_programs.php",
                    method: "POST",
                    data: {
                        educational_level: $($input_sectionEducationalLevel).val(),
                    },
                    success: function (response) {
                        $($input_sectionPrograms).empty();
                        response.data.forEach((item) => {
                            // console.log(item.program_name);
                            $($input_sectionPrograms).append(
                                `<option value="${item.program_id}">${item.program_code} | ${item.program_name}</option>`
                            );
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
                    },
                });
            });

            // Select2 Adviser
            const $oldSectionAdviserValue = $($input_sectionAdviser).val;


            // let oldSectionAdviserText = "Old Adviser Name"; // Replace with the predetermined display text
            // Initialize Select2
            $($input_sectionAdviser).select2({
                placeholder: $oldSectionAdviserValue, // Optional: Provide a placeholder if no value is selected
                allowClear: true,
                ajax: {
                    url: "",
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            search_type: "teacher",
                            query: params.term,
                            additional_filters: {
                                educational_level: $($input_sectionEducationalLevel).val(), // Filter by educational level
                            },
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map((teacher) => ({
                                id: teacher.user_id,
                                text: `${teacher.name} (${teacher.user_id})`,
                            })),
                        };
                    },
                },
            });

            $("#input_modalAddToEnrollStudents").select2({
                placeholder: "Search students to add", // Optional: Provide a placeholder if no value is selected
                ajax: {
                    url: "", // Empty URL to use the current URL
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            search_type: "student",
                            query: params.term, // Search query from user input
                            additional_filters: {
                                educational_level: $($input_sectionEducationalLevel).val(), // Filter by educational level
                            },
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (student) {
                                return {
                                    id: student.user_id,
                                    text: `${student.name} (${student.user_id})`
                                };
                            })
                        };
                    }
                }
            });
            $("#input_addToEnrollSubjects").select2({
                placeholder: "Search subjects to add", // Optional: Provide a placeholder if no value is selected
                ajax: {
                    url: "", // Empty URL to use the current URL
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            search_type: "subject",
                            query: params.term, // Search query from user input
                            additional_filters: {
                                educational_level: $($input_sectionEducationalLevel).val(), // Filter by educational level
                            },
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (subject) {
                                return {
                                    id: subject.subject_id,
                                    text: `${subject.name} (${subject.subject_id})`
                                };
                            })
                        };
                    }
                }
            });
            $("#input_addTeacherToSubjectEnrollment").select2({
                placeholder: "Search subject teacher to add", // Optional: Provide a placeholder if no value is selected
                ajax: {
                    url: "", // Empty URL to use the current URL
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            search_type: "teacher",
                            query: params.term, // Search query from user input
                            additional_filters: {
                                educational_level: $($input_sectionEducationalLevel).val(), // Filter by educational level
                            },
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (teacher) {
                                return {
                                    id: teacher.user_id,
                                    text: `${teacher.name} (${teacher.user_id})`
                                };
                            })
                        };
                    }
                }
            });

        },
    },
};

$(document).ready(function () {
    let BTN_EDIT = $("#dynamic_btn_edit");
    let BTN_SAVE = $("#dynamic_btn_save");
    let BTN_CANCEL = $("#dynamic_btn_cancel");

    // Array to store original values of form elements
    let originalValues = [];

    let DYNAMIC_FORM = $("[dynamic-form-id]");
    // console.log(DYNAMIC_FORM[0]);
    if (DYNAMIC_FORM[0] !== undefined) {
        dynamic_form_ids[DYNAMIC_FORM.attr("dynamic-form-id")]["initFunc"](DYNAMIC_FORM[0]);
    } else {
        return;
    }


    function init() {
        BTN_EDIT.addClass("d-block");
        BTN_SAVE.addClass("d-none");
        BTN_CANCEL.addClass("d-none");
        setStateUpdate(false); // Ensure form elements are disabled initially
    }

    function setStateUpdate(state) {
        if ($.type(state) != "boolean") return;

        $("[update-enabled]").each(function () {
            if ($(this).is("input, textarea, select, button, a")) {
                $(this).prop("disabled", !state);
                $(this).toggleClass("disabled", !state);
            }
        });
    }

    BTN_EDIT.on("click", function () {
        BTN_EDIT.removeClass("d-block").addClass("d-none");
        BTN_SAVE.removeClass("d-none").addClass("d-block");
        BTN_CANCEL.removeClass("d-none").addClass("d-block");

        // Store the current values of all relevant form elements with `update-enabled`
        originalValues = [];
        $("[update-enabled]").each(function () {
            const element = $(this);
            if (element.is("input, textarea, select")) {
                originalValues.push({
                    element: element,
                    value: element.text(), // Store current value
                    defaultOption: element.is("select")
                        ? element.find(":selected").val()
                        : null, // Store default for selects
                });
            }
        });

        setStateUpdate(true);
    });

    BTN_CANCEL.on("click", function () {
        let makeUpdate = confirm(
            "Are you sure you do not want to update this information?"
        );
        if (makeUpdate) {
            location.reload();
        }
    });

    BTN_SAVE.on('click', function () {
        makeToast('default', 'submitting section configuration...')
    });

    init();
});
