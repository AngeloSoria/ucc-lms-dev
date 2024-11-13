/**
 * Opens a preview panel displaying the provided image data.
 *
 * @param {string} imagePath - The image data (URL or base64 encoded) to be displayed in the preview panel.
 *
 * @returns {void}
 *
 * The function creates a new div element with the class 'image-preview-panel' and appends it to the body.
 * Inside the preview panel, it creates a controls div with a close button and a preview container div.
 * The close button is added an event listener to call the 'closePreview' function when clicked.
 * The image data is then used to create an img element and append it to the preview container.
 * Finally, the 'no-scroll' class is added to the body to prevent scrolling while the preview panel is open.
 */
function openPreview(imagePath) {
  console.log("test");

  const footer = document.querySelector("footer");
  // Check for existing preview panel.
  if (document.querySelector(".image-preview-panel")) {
    return;
  }

  let previewPanel = document.createElement("div");
  previewPanel.classList.add("image-preview-panel", "z-10");

  let controls = document.createElement("div");
  controls.classList.add("controls");
  previewPanel.appendChild(controls);

  let btnClose = document.createElement("button");
  btnClose.classList.add("btn", "btn-transparent");
  btnClose.innerHTML = '<i class="bi bi-x fs-1 text-white"></i>';
  btnClose.addEventListener("click", closePreview);
  controls.appendChild(btnClose);

  let previewContainer = document.createElement("div");
  previewContainer.classList.add("preview-container");
  previewPanel.appendChild(previewContainer);

  let imgPreview = document.createElement("img");
  imgPreview.src = imagePath;
  previewContainer.appendChild(imgPreview);

  if (!footer) {
    document.body.appendChild(previewPanel);
  } else {
    document.body.insertBefore(previewPanel, footer);
  }
  document.body.classList.add("no-scroll");
}

/**
 * Closes the image preview panel and removes the 'no-scroll' class from the body.
 *
 * @returns {void}
 *
 * This function is responsible for removing the image preview panel from the DOM and
 * restoring the scrolling functionality of the page. It does this by selecting the
 * '.image-preview-panel' element, removing it from the body, and then removing the
 * 'no-scroll' class from the body.
 */
function closePreview() {
  document.querySelector(".image-preview-panel").remove();
  document.body.classList.remove("no-scroll");
}
