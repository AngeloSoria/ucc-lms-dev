
// Nav Sidebar
nav_sidebar = document.querySelector('#navSideBar');
nav_btnOpenNavSideBarMobile = document.querySelector('#btnOpenNavSideBarMobile');
nav_btnCloseNavSideBarMobile = document.querySelector('#btnCloseNavSideBarMobile');

nav_btnOpenNavSideBarMobile.addEventListener('click', () => {
    nav_sidebar.classList.add('sidebar-active');
})
nav_btnCloseNavSideBarMobile.addEventListener('click', () => {
    nav_sidebar.classList.remove('sidebar-active');
})