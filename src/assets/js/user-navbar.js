// Sidebar menu script toggle.
function toggleSidebar() {
    const sidebar = $('#sidebarMenu');
    const content = $('#contentSection');

    sidebar.toggleClass('sidebar-active');
}
$('#btnSideBarMenu').on('click', function () {
    toggleSidebar();
});