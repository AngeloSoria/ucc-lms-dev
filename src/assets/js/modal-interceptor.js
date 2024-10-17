// $(document).ready(function() {
//     // Event listener for modal hide event
//     $('.modal').on('hide.bs.modal', function(e) {
//         // Check if the modal has the closing-confirmation attribute
//         if ($(this).attr('closing-confirmation') !== undefined) {
//             // Get the custom confirmation text
//             var confirmationText = $(this).attr('closing-confirmation-text') || "Are you sure you want to close this modal?";
            
//             // Show confirmation dialog with the custom text
//             var confirmation = confirm(confirmationText);
            
//             // If the user clicks "Cancel", prevent the modal from closing
//             if (!confirmation) {
//                 e.preventDefault(); // Prevent the modal from closing
//             }
//         }
//     });
// });