const ATTRIBUTE_NAME = 'data-bs-target';

function apply_section_modal(element) {
    // Use jQuery to get the attribute
    let getSectionModalID = $(element).attr(ATTRIBUTE_NAME);

    if (!getSectionModalID) {
        console.warn(`No ${ATTRIBUTE_NAME} found for`, element);
        return;
    }

    // Use jQuery to find the section modal
    let getSectionModal = $(getSectionModalID);
    if (getSectionModal.length === 0) {
        console.warn(`No section_modal found in the document.`);
        return;
    }

    // Use jQuery to find all sections with class 'form-step'
    let getAllSectionsFromModal = getSectionModal.find(".form-step");
    let totalSteps = getAllSectionsFromModal.length;

    // Get Buttons
    let btnPrevious = getSectionModal.find('#btnPrevious');
    let btnNext = getSectionModal.find('#btnNext');
    let btnSubmit = getSectionModal.find('#btnSubmit');

    // Get Title Counter
    let allowTitleCounter = getSectionModal.attr('section-counter-show');
    let oldTitle; // Initialize oldTitle variable
    if (allowTitleCounter) {
        var titleCounter = getSectionModal.find('.modal-title');
        if (allowTitleCounter === 'true') {
            oldTitle = titleCounter.text().replace(/ \(\d+\/\d+\)/, ''); // Remove any existing counter
        }
    }

    // Progress bar
    let progressBar = getSectionModal.find('#sectionProgressBar');

    // Show first step and hide other steps.
    getAllSectionsFromModal.each(function (index) {
        if (index === 0) {
            $(this).removeClass('d-none').addClass('d-block');
        } else {
            $(this).removeClass('d-block').addClass('d-none');
        }
    });

    let currentStep = 0;
    function showStep(step) {
        if (step < 0 || step > totalSteps - 1) {
            console.error('Invalid step number:', step);
            return;
        }

        getAllSectionsFromModal.each(function (index) {
            if (index === step) {
                $(this).removeClass('d-none').addClass('d-block');
            } else {
                $(this).removeClass('d-block').addClass('d-none');
            }
        });

        // Show/hide buttons based on the current step
        if (totalSteps === 1) { // Only one step
            btnPrevious.removeClass('d-inline-block').addClass('d-none');
            btnNext.removeClass('d-inline-block').addClass('d-none');
            btnSubmit.removeClass('d-inline-block').addClass('d-inline-block'); // Show submit button
        } else if (currentStep === 0) { // First step
            btnPrevious.removeClass('d-inline-block').addClass('d-none');
            btnNext.removeClass('d-none').addClass('d-inline-block');
            btnSubmit.removeClass('d-inline-block').addClass('d-none');
        } else if (currentStep === totalSteps - 1) { // Last step
            btnNext.removeClass('d-inline-block').addClass('d-none');
            btnPrevious.removeClass('d-none').addClass('d-inline-block');
            btnSubmit.removeClass('d-none').addClass('d-inline-block');
        } else { // Middle steps
            btnPrevious.removeClass('d-none').addClass('d-inline-block');
            btnNext.removeClass('d-none').addClass('d-inline-block');
            btnSubmit.removeClass('d-inline-block').addClass('d-none');
        }

        // Text counter
        if (allowTitleCounter) {
            titleCounter.text(oldTitle + " (" + (currentStep + 1) + "/" + totalSteps + ")");
        }

        // Progress bar indicator
        let progressPercentage = ((currentStep + 1) / totalSteps) * 100; // Calculate progress
        progressBar.find('.progress-bar')
            .css('width', progressPercentage + '%') // Update width
            .attr('aria-valuenow', progressPercentage); // Update aria-valuenow
    }


    function preventOutboundIndex(request) {
        if (request === 'increment') {
            if (currentStep < totalSteps - 1) { // prevent going out of bounds
                currentStep += 1;
            }
        } else if (request === 'decrement') {
            if (currentStep > 0) {
                currentStep -= 1;
            }
        }
    }

    // Initial step display
    showStep(currentStep);

    // Event bindings for buttons
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
}
