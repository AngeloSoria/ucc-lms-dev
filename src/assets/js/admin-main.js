

// SUBMENU ACCORDIONS (Sidebar)
// Select all main submenu containers
$('.submenu-main').each(function() {
    // Add a click event listener for each submenu toggle
    $(this).find('.submenu-toggle').on('click', function(e) {
        e.preventDefault();

        // Log the state of the submenu content
        var submenu_content = $(e.target).closest('.submenu-main').find('.submenu-content');
        
        // Toggle the clicked submenu and its icon
        var submenu_dropdown_icon = $(e.target).closest('.submenu-main').find('.dropdownIcon');
        
        if (submenu_content.hasClass('submenu-active')) {
            submenu_content.removeClass('submenu-active'); // Close the submenu
            submenu_dropdown_icon.removeClass('rotate'); // Reset the chevron
        } else {
            submenu_content.addClass('submenu-active'); // Open the submenu
            submenu_dropdown_icon.addClass('rotate'); // Rotate the chevron
        }
    });
});


// Sidebar menu script toggle.
$('#btnSideBarMenu').on('click', function() {
    const sidebar = $('#sidebarMenu');
    const content = $('#contentSection');
    
    sidebar.toggleClass('sidebar-hidden');

    // Adjust content width
    // if (sidebar.hasClass('sidebar-hidden')) {
    //     content.css('flex', '1');
    // } else {
    //     content.css('flex', 'auto');
    // }
});




// Users Page
var data_view_catalog = $('#data_view_catalog');
var data_view_table = $('#data_view_table');
var btn_view_catalog = $('#btnViewTypeCatalog');
var btn_view_table = $('#btnViewTypeTable');

btn_view_catalog.on('click', function() {
    console.log("btn_view_catalog");
    
    data_view_catalog.removeClass('d-none');
    data_view_table.addClass('d-none');

    btn_view_catalog.addClass('btn-primary');
    btn_view_catalog.removeClass('btn-outline-primary');
    btn_view_table.addClass('btn-outline-primary');
    btn_view_table.removeClass('btn-primary');
})

btn_view_table.on('click', function() {
    console.log("btn_view_table");
    
    data_view_catalog.addClass('d-none');
    data_view_table.removeClass('d-none');

    btn_view_table.addClass('btn-primary');
    btn_view_table.removeClass('btn-outline-primary');
    btn_view_catalog.addClass('btn-outline-primary');
    btn_view_catalog.removeClass('btn-primary');
})
// =================================================================