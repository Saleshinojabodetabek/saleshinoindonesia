document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector(".carousel");
  const btnLeft = document.querySelector(".carousel-btn.left");
  const btnRight = document.querySelector(".carousel-btn.right");

  if (!carousel || !btnLeft || !btnRight) return;

  const productItems = document.querySelectorAll(".product-item");
  const itemWidth = productItems[0].offsetWidth + 20; // termasuk gap antar gambar
  const visibleItems = 4;
  let currentIndex = 0;

  // Fungsi untuk menggeser carousel
  function moveCarousel() {
    currentIndex++;

    // jika melebihi batas akhir, reset ke awal
    if (currentIndex > productItems.length - visibleItems) {
      currentIndex = 0;
    }

    carousel.scrollTo({
      left: currentIndex * itemWidth,
      behavior: "smooth",
    });
  }

  // Slide otomatis
  let autoSlide = setInterval(moveCarousel, 3000);

  // Fungsi restart interval (digunakan saat klik manual)
  function restartInterval() {
    clearInterval(autoSlide);
    autoSlide = setInterval(moveCarousel, 3000);
  }

  // Geser kiri manual
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

  // Geser kanan manual
  btnRight.addEventListener("click", () => {
    moveCarousel();
    restartInterval();
  });
});

// Aplikasi
document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector(".applications-carousel .carousel");
  const btnLeft = document.querySelector(
    ".applications-carousel .carousel-btn.left"
  );
  const btnRight = document.querySelector(
    ".applications-carousel .carousel-btn.right"
  );

  if (!carousel || !btnLeft || !btnRight) return;

  const productItems = document.querySelectorAll(
    ".applications-carousel .product-item"
  );
  const itemWidth = productItems[0].offsetWidth + 20; // termasuk gap antar gambar
  const visibleItems = 4;
  let currentIndex = 0;

  // Fungsi untuk menggeser carousel
  function moveCarousel() {
    currentIndex++;

    // jika melebihi batas akhir, reset ke awal
    if (currentIndex > productItems.length - visibleItems) {
      currentIndex = 0;
    }

    carousel.scrollTo({
      left: currentIndex * itemWidth,
      behavior: "smooth",
    });
  }

  // Slide otomatis
  let autoSlide = setInterval(moveCarousel, 3000);

  // Fungsi restart interval (digunakan saat klik manual)
  function restartInterval() {
    clearInterval(autoSlide);
    autoSlide = setInterval(moveCarousel, 3000);
  }

  // Geser kiri manual
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

  // Geser kanan manual
  btnRight.addEventListener("click", () => {
    moveCarousel();
    restartInterval();
  });
});

// Hamburger Menu
document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.querySelector(".hamburger-menu");
  const navLinks = document.querySelector(".nav.links");

  // Toggle menu saat hamburger diklik
  hamburger.addEventListener("click", (e) => {
    e.stopPropagation(); // Mencegah event bubbling agar tidak langsung tertutup
    navLinks.classList.toggle("active");
  });

  // Klik di luar nav & hamburger akan menutup menu
  document.addEventListener("click", (e) => {
    if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
      navLinks.classList.remove("active");
    }
  });
});
