document.addEventListener("DOMContentLoaded", function () {
  const slideTrack = document.getElementById("slide-track");
  const slides = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot");
  const goLeft = document.getElementById("go-left");
  const goRight = document.getElementById("go-right");

  const totalSlides = slides.length;
  const slidesToShow = 3;
  let currentIndex = 0;
  let autoSlide;

  function updateCarousel() {
    const slideWidth = slideTrack.clientWidth / slidesToShow;
    slideTrack.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

    dots.forEach((dot, i) => {
      dot.classList.toggle("active", i === currentIndex);
    });
  }

  function goToNext() {
    if (currentIndex < totalSlides - slidesToShow) {
      currentIndex++;
    } else {
      currentIndex = 0;
    }
    updateCarousel();
  }

  function goToPrev() {
    if (currentIndex > 0) {
      currentIndex--;
    } else {
      currentIndex = totalSlides - slidesToShow;
    }
    updateCarousel();
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    autoSlide = setInterval(goToNext, 5000);
  }

  goLeft?.addEventListener("click", (e) => {
    e.stopPropagation();
    goToPrev();
    resetAutoSlide();
  });

  goRight?.addEventListener("click", (e) => {
    e.stopPropagation();
    goToNext();
    resetAutoSlide();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      currentIndex = index;
      updateCarousel();
      resetAutoSlide();
    });
  });

  updateCarousel();
  autoSlide = setInterval(goToNext, 3000);
});
