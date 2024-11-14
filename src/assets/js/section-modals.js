const ATTRIBUTE_NAME = 'data-bs-target';

function apply_section_modal(element) {
    let getSectionModalID = $(element).attr(ATTRIBUTE_NAME);

    if (!getSectionModalID) {
        console.warn(`No ${ATTRIBUTE_NAME} found for`, element);
        return;
    }

    let getSectionModal = $(getSectionModalID);
    if (getSectionModal.length === 0) {
        console.warn(`No section_modal found in the document.`);
        return;
    }

    let getAllSectionsFromModal = getSectionModal.find(".form-step");
    let totalSteps = getAllSectionsFromModal.length;

    let btnPrevious = getSectionModal.find('#btnPrevious');
    let btnNext = getSectionModal.find('#btnNext');
    let btnSubmit = getSectionModal.find('#btnSubmit');

    let allowTitleCounter = getSectionModal.attr('section-counter-show');
    let oldTitle;
    if (allowTitleCounter) {
        var titleCounter = getSectionModal.find('.modal-title');
        if (allowTitleCounter === 'true') {
            oldTitle = titleCounter.text().replace(/ \(\d+\/\d+\)/, '');
        }
    }

    let progressBar = getSectionModal.find('#sectionProgressBar');

    getAllSectionsFromModal.each(function (index) {
        $(this).toggleClass('d-block', index === 0).toggleClass('d-none', index !== 0);
    });

    let currentStep = 0;

    function showStep(step) {
        if (step < 0 || step >= totalSteps) {
            console.error('Invalid step number:', step);
            return;
        }

        getAllSectionsFromModal.each(function (index) {
            $(this).toggleClass('d-block', index === step).toggleClass('d-none', index !== step);
        });

        btnPrevious.toggleClass('d-none', step === 0);
        btnNext.toggleClass('d-none', step === totalSteps - 1);
        btnSubmit.toggleClass('d-none', step !== totalSteps - 1);

        if (allowTitleCounter) {
            titleCounter.text(oldTitle + " (" + (currentStep + 1) + "/" + totalSteps + ")");
        }

        let progressPercentage = ((currentStep + 1) / totalSteps) * 100;
        progressBar.find('.progress-bar').css('width', progressPercentage + '%').attr('aria-valuenow', progressPercentage);
    }

    function isStepComplete(step) {
        let allInputs = getAllSectionsFromModal.eq(step).find('input, select, textarea');
        let incomplete = false;

        allInputs.each(function () {
            let input = $(this);
            let value = input.val();
            let min = input.attr('min');
            let max = input.attr('max');
            let type = input.attr('type'); // Get the input type (text, date, number, etc.)

            // Reset the is-invalid class and error message by default
            input.removeClass('is-invalid');
            input.next('.invalid-feedback').hide();

            // Handle required field validation
            if (input.is(':required') && !input.is(':disabled') && !input.is('[readonly]') && input.is(':visible') && !value) {
                incomplete = true;
                input.addClass('is-invalid');
                input.next('.invalid-feedback').text('This field is required.').show();
            }

            // Date and number validation for min attribute
            if (min && value) {
                if (type === "date") {
                    let minDate = new Date(min);
                    let valueDate = new Date(value);
                    if (valueDate < minDate) {
                        console.log("WOO");

                        incomplete = true;
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').text(`Date must be on or after ${min}.`).show();
                        makeToast("warning", `Date must be on or after ${min}.`);
                    }
                } else if (parseFloat(value) < parseFloat(min)) {
                    console.log("WAA");

                    incomplete = true;
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(`Value must be greater than or equal to ${min}.`).show();
                    makeToast("warning", `Value must be greater than or equal to ${min}.`);
                }
            }

            // Date and number validation for max attribute
            if (max && value) {
                if (type === "date") {
                    let maxDate = new Date(max);
                    let valueDate = new Date(value);
                    if (valueDate > maxDate) {
                        console.log("FOO");

                        incomplete = true;
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').text(`Date must be on or before ${max}.`).show();
                        makeToast("warning", `Date must be on or before ${max}.`);
                    }
                } else if (parseFloat(value) > parseFloat(max)) {
                    console.log("BAR");

                    incomplete = true;
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(`Value must be less than or equal to ${max}.`).show();
                    makeToast("warning", `Value must be less than or equal to ${max}.`);
                }
            }
        });

        return !incomplete;
    }

    function preventOutboundIndex(request) {
        if (request === 'increment' && currentStep < totalSteps - 1) {
            if (isStepComplete(currentStep)) {
                currentStep += 1;
            } else {
                makeToast('warning', 'Please complete all required fields in this step.', 3000);
            }
        } else if (request === 'decrement' && currentStep > 0) {
            currentStep -= 1;
        }
    }

    showStep(currentStep);

    btnPrevious.on('click', function (e) {
        e.preventDefault();
        preventOutboundIndex('decrement');
        showStep(currentStep);
    });

    btnNext.on('click', function (e) {
        e.preventDefault();
        preventOutboundIndex('increment');
        showStep(currentStep);
    });

    getSectionModal.find('input, select, textarea').on('input', function () {
        let input = $(this);
        input.removeClass('is-invalid');
        input.next('.invalid-feedback').hide();
    });
}
