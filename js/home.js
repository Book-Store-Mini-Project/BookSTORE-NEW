// Silder case=========>
let slideIndex = 1;

function renderSlider() {
  // Slideshow setup
  showSlides(slideIndex);

  // Add event listeners for the dots
  const dots = document.getElementsByClassName("dot");
  for (let i = 0; i < dots.length; i++) {
    dots[i].addEventListener("click", function() {
      currentSlide(i + 1); // Click on a dot, go to the respective slide
    });
  }
  
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");

    // If we exceed the total number of slides, start again from 1
    if (n > slides.length) {
      slideIndex = 1;
    }
    // If we go below 1, go to the last slide
    if (n < 1) {
      slideIndex = slides.length;
    }

    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }

    // Remove the "active" class from all dots
    for (let i = 0; i < dots.length; i++) {
      dots[i].classList.remove("active");
    }

    // Show the current slide and activate the corresponding dot
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].classList.add("active");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  //slider call
  renderSlider();
});
