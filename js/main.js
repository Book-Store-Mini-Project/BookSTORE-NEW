document.addEventListener("DOMContentLoaded", function () {
    const nav = document.getElementById("nav");
    const hamburger = document.getElementById("hamburger");
    const closeIcon = document.getElementById("close-icon");

    hamburger.addEventListener("click", () => {
        nav.classList.add("show-nav");
    });

    closeIcon.addEventListener("click", () => {
        nav.classList.remove("show-nav");
    });
});
