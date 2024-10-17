// NAVIGATION
let isNavOpen = false;
function toggleNav() {
    $("nav").toggleClass("nav-expanded");

    let icon = $(".btn-menu").find(".icon");
    icon.text(isNavOpen ? "menu" : "close");
    isNavOpen = !isNavOpen;
}
$(".btn-menu").on('click', toggleNav);

// CAROUSEL
// const bgCarousel = document.querySelector(".bgCarousel");
// const bgcImgContent = bgCarousel.querySelector(".imgContent");
// const carouselPaginator = bgCarousel.querySelector(".paginator");
// const carouselPaginatorBullets = carouselPaginator.querySelector(".bullets");
// const carouselBtnControlLeft = carouselPaginator.querySelector("#control-left");
// const carouselBtnControlRight = carouselPaginator.querySelector("#control-right");

let imgResources = [];

// Fetch images from the server
/* function loadImages() {
    fetch('src/controllers/fetch_images.php')
        .then(response => response.json())
        .then(data => {
            let imgResources = data;

            // Clear previous content
            $('.carousel-inner').empty();
            $('.carousel-indicators').empty();

            // Load images into the carousel
            imgResources.forEach((image, index) => {
                // Create carousel item
                const carouselItem = $("<div></div>", {
                    class: `carousel-item ${index === 0 ? "active" : ""}`
                });

                const imgElement = $("<img/>", {
                    class: "d-block w-100",
                    src: image.image_data,
                    alt: image.image_name
                });

                carouselItem.append(imgElement);
                $('.carousel-inner').append(carouselItem);

                // Create carousel indicators (bullets)
                const indicator = $("<button></button>", {
                    type: "button",
                    class: `${index === 0 ? "active" : ""}`,
                    "data-bs-target": "#carouselExampleFade",
                    "data-bs-slide-to": index,
                    "aria-current": index === 0 ? "true" : "false",
                    "aria-label": `Slide ${index + 1}`
                });
                $('.carousel-indicators').append(indicator);
            });

        })
        .catch(error => console.error('Error fetching images:', error));
}

// function loadImages() {
//     fetch('src/controllers/fetch_images.php')

//         .then(response => response.json())
//         .then(data => {
//             imgResources = data;

//             // Clear previous content
//             $(bgcImgContent).empty();
//             $(carouselPaginatorBullets).empty();

//             // Load images into the carousel
//             imgResources.forEach((image, index) => {
//                 // Create image
//                 const thisImage = $("<div></div>", {
//                     class: "content",
//                     css: {
//                         backgroundImage: `url('${image.image_data}')`
//                     },
//                     alt: image.image_name
//                 });

//                 if (index === 0) {
//                     thisImage.addClass("active");
//                 }

//                 $(bgcImgContent).append(thisImage);

//                 // Create carousel bullets
//                 const bullet = $("<div></div>", {
//                     class: "bullet",
//                 });
//                 bullet.append("<span class='material-symbols-outlined'>radio_button_unchecked</span>");
//                 $(carouselPaginatorBullets).append(bullet);
//             });

//             // Initialize bullet states
//             $(".bullet span").first().text("radio_button_checked");
//         })
//         .catch(error => console.error('Error fetching images:', error));
// }
*/
let currentIndex = 0;
let inCooldown = false;

function changeImage(request) {
    if (inCooldown) return;
    inCooldown = true;

    if (request === "next") {
        currentIndex = (currentIndex + 1) % imgResources.length;
    } else if (request === "prev") {
        currentIndex = (currentIndex - 1 + imgResources.length) % imgResources.length;
    }

    // Update images
    $(".content").removeClass("active").eq(currentIndex).addClass("active");

    // Update bullets
    $(".bullet span").text("radio_button_unchecked").eq(currentIndex).text("radio_button_checked");

    setTimeout(() => {
        inCooldown = false;
    }, 1000);
}

$("#control-left").on('click', function () {
    changeImage("prev");
});

$("#control-right").on('click', function () {
    changeImage("next");
});

// AUTOMATIC CAROUSEL
$(window).on('load', function () {
    loadImages(); // Load images when the window loads
    setInterval(() => {
        changeImage("next");
    }, 7000);
});

// Login POPUP
$loginModal = $(".popup");
$("#btnLogin").on('click', function () {
    if ($loginModal.is(":hidden")) {
        $loginModal.fadeIn(100);
        toggleNav();
    } else {
        $loginModal.fadeOut(100);
    }
});
$("#btnPopupLoginClose").on('click', function () {
    $loginModal.fadeOut(100);
});
