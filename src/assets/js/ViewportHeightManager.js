function setVh() {
    // Calculate 1vh as a percentage of the current viewport height
    let vh = window.innerHeight;
    // Set the value in the --vh custom property to the root element
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

// Execute the function initially
setVh();

// Update the value on window resize
window.addEventListener('resize', setVh);