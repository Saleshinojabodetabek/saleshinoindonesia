document.addEventListener("DOMContentLoaded", function () {
  // === CAROUSEL PRODUK ===
  const productCarousel = document.querySelector(".carousel");
  const productBtnLeft = document.querySelector(".carousel-btn.left");
  const productBtnRight = document.querySelector(".carousel-btn.right");

  if (productCarousel && productBtnLeft && productBtnRight) {
    const productItems = document.querySelectorAll(".product-item");
    const productItemWidth = productItems[0].offsetWidth + 20; // dengan gap
    const visibleItems = 4;
    let currentIndex = 0;

    function moveProductCarousel() {
      currentIndex++;
      if (currentIndex > productItems.length - visibleItems) {
        currentIndex = 0;
      }
      productCarousel.scrollTo({
        left: currentIndex * productItemWidth,
        behavior: "smooth",
      });
    }

    let autoSlideProduct = setInterval(moveProductCarousel, 3000);

    function restartProductInterval() {
      clearInterval(autoSlideProduct);
      autoSlideProduct = setInterval(moveProductCarousel, 3000);
    }

    productBtnLeft.addEventListener("click", () => {
      currentIndex =
        currentIndex === 0
          ? productItems.length - visibleItems
          : currentIndex - 1;
      productCarousel.scrollTo({
        left: currentIndex * productItemWidth,
        behavior: "smooth",
      });
      restartProductInterval();
    });

    productBtnRight.addEventListener("click", () => {
      moveProductCarousel();
      restartProductInterval();
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
    const appItemWidth = appItems[0].offsetWidth + 20;
    const visibleItems = 4;
    let currentAppIndex = 0;

    function moveAppCarousel() {
      currentAppIndex++;
      if (currentAppIndex > appItems.length - visibleItems) {
        currentAppIndex = 0;
      }
      appCarousel.scrollTo({
        left: currentAppIndex * appItemWidth,
        behavior: "smooth",
      });
    }

    let autoSlideApp = setInterval(moveAppCarousel, 3000);

    function restartAppInterval() {
      clearInterval(autoSlideApp);
      autoSlideApp = setInterval(moveAppCarousel, 3000);
    }

    appBtnLeft.addEventListener("click", () => {
      currentAppIndex =
        currentAppIndex === 0
          ? appItems.length - visibleItems
          : currentAppIndex - 1;
      appCarousel.scrollTo({
        left: currentAppIndex * appItemWidth,
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

  // === FORM KONTAK (AJAX SUBMIT) ===
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(contactForm);

      fetch("contact.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          document.getElementById("form-message").innerText = data;
          document.getElementById("form-message").style.color = "green";
          contactForm.reset();
        })
        .catch((error) => {
          document.getElementById("form-message").innerText =
            "Gagal mengirim pesan.";
          document.getElementById("form-message").style.color = "red";
        });
    });
  }

  // === FEATHER ICONS ===
  if (typeof feather !== "undefined") {
    feather.replace();
  }
});