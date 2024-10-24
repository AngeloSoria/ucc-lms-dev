// NAVIGATION
let isNavOpen = false;
function toggleNav() {
  $("nav").toggleClass("nav-expanded");

  let icon = $(".btn-menu").find(".icon");
  icon.text(isNavOpen ? "menu" : "close");
  isNavOpen = !isNavOpen;
}
$(".btn-menu").on("click", toggleNav);

// Login POPUP
$loginModal = $(".popup");
$("#btnLogin").on("click", function () {
  if ($loginModal.is(":hidden")) {
    $loginModal.fadeIn(100);
    toggleNav();
  } else {
    $loginModal.fadeOut(100);
  }
});
$("#btnPopupLoginClose").on("click", function () {
  $loginModal.fadeOut(100);
});
