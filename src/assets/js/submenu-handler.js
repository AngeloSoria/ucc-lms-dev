// SUBMENU ACCORDIONS (Sidebar)
// Select all main submenu containers
$('.submenu-main').each(function () {
    // Add a click event listener for each submenu toggle
    $(this).find('.submenu-toggle').on('click', function (e) {
        e.preventDefault();

        // Log the state of the submenu content
        var submenu_content = $(e.target).closest('.submenu-main').find('.submenu-content');

        // Toggle the clicked submenu and its icon
        var submenu_dropdown_icon = $(e.target).closest('.submenu-main').find('.dropdownIcon .icon');

        if (submenu_content.hasClass('submenu-active')) {
            submenu_content.removeClass('submenu-active'); // Close the submenu
            submenu_dropdown_icon.removeClass('rotate'); // Reset the chevron
        } else {
            submenu_content.addClass('submenu-active'); // Open the submenu
            submenu_dropdown_icon.addClass('rotate'); // Rotate the chevron
        }
    });
});