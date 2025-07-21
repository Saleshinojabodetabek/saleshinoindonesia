document.addEventListener("DOMContentLoaded", function () {
  // === CAROUSEL UTAMA ===
  const carousel = document.querySelector(".carousel");
  const btnLeft = document.querySelector(".carousel-btn.left");
  const btnRight = document.querySelector(".carousel-btn.right");

  if (carousel && btnLeft && btnRight) {
    const productItems = document.querySelectorAll(".product-item");
    const itemWidth = productItems[0].offsetWidth + 20; // termasuk margin/gap
    const visibleItems = 1;
    let currentIndex = 0;

    function moveCarousel() {
      currentIndex++;
      if (currentIndex > productItems.length - visibleItems) {
        currentIndex = 0;
      }
      carousel.scrollTo({
        left: currentIndex * itemWidth,
        behavior: "smooth",
      });
    }

    let autoSlide = setInterval(moveCarousel, 3000);

    function restartInterval() {
      clearInterval(autoSlide);
      autoSlide = setInterval(moveCarousel, 3000);
    }

    btnLeft.addEventListener("click", () => {
      if (currentIndex === 0) {
        currentIndex = productItems.length - visibleItems;
      } else {
        currentIndex--;
      }
      carousel.scrollTo({
        left: currentIndex * itemWidth,
        behavior: "smooth",
      });
      restartInterval();
    });

    btnRight.addEventListener("click", () => {
      moveCarousel();
      restartInterval();
    });
  }

  // === CAROUSEL APLIKASI ===
  const appCarousel = document.querySelector(
    ".applications-carousel .carousel"
  );
  const appBtnLeft = document.querySelector(
    ".applications-carousel .carousel-btn.left"
  );
  const appBtnRight = document.querySelector(
    ".applications-carousel .carousel-btn.right"
  );

  if (appCarousel && appBtnLeft && appBtnRight) {
    const appItems = document.querySelectorAll(
      ".applications-carousel .product-item"
    );
    const itemWidth = appItems[0].offsetWidth + 20;
    const visibleItems = 1;
    let appIndex = 0;

    function moveAppCarousel() {
      appIndex++;
      if (appIndex > appItems.length - visibleItems) {
        appIndex = 0;
      }
      appCarousel.scrollTo({
        left: appIndex * itemWidth,
        behavior: "smooth",
      });
    }

    let autoAppSlide = setInterval(moveAppCarousel, 3000);

    function restartAppInterval() {
      clearInterval(autoAppSlide);
      autoAppSlide = setInterval(moveAppCarousel, 3000);
    }

    appBtnLeft.addEventListener("click", () => {
      if (appIndex === 0) {
        appIndex = appItems.length - visibleItems;
      } else {
        appIndex--;
      }
      appCarousel.scrollTo({
        left: appIndex * itemWidth,
        behavior: "smooth",
      });
      restartAppInterval();
    });

    appBtnRight.addEventListener("click", () => {
      moveAppCarousel();
      restartAppInterval();
    });
  }

  // === HAMBURGER MENU ===
  const hamburger = document.querySelector(".hamburger-menu");
  const navLinks = document.querySelector(".nav.links");

  if (hamburger && navLinks) {
    hamburger.addEventListener("click", (e) => {
      e.stopPropagation();
      navLinks.classList.toggle("active");
    });

    document.addEventListener("click", (e) => {
      if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
        navLinks.classList.remove("active");
      }
    });
  }

  // === SLIDE BANNER (Jika pakai .slide class) ===
  const slides = document.querySelectorAll(".slide");
  if (slides.length > 0) {
    let currentSlide = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === index);
      });
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }

    setInterval(nextSlide, 4000);
  }
});
