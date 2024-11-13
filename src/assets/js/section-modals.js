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
            if (input.is(':required') && !input.is(':disabled') && !input.is('[readonly]') && input.is(':visible') && !input.val()) {
                incomplete = true;
                input.addClass('is-invalid');
                input.next('.invalid-feedback').text('This field is required.').show();
            } else {
                input.removeClass('is-invalid');
                input.next('.invalid-feedback').hide();
            }
        });

        return !incomplete;
    }

    function preventOutboundIndex(request) {
        if (request === 'increment' && currentStep < totalSteps - 1) {
            if (isStepComplete(currentStep)) {
                currentStep += 1;
            } else {
                showToast('warning', 'Please complete all required fields in this step.', 3000);
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
        if (input.val()) {
            input.removeClass('is-invalid');
            input.next('.invalid-feedback').hide();
        }
    });
}
