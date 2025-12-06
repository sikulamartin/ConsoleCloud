window.addEventListener("scroll", () => {
  const header = document.getElementById("header");
  if (window.scrollY > 0) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});

window.addEventListener("DOMContentLoaded", () => {
  const header = document.getElementById("header");
  if (window.scrollY > 0) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});

window.addEventListener("DOMContentLoaded", () => {
  const el = document.getElementById("header");
  el.classList.add("animated");
  function recalcLayout() {
    const headerHeight = parseFloat(getComputedStyle(el).height);
    document.documentElement.style.setProperty(
      "--header-height",
      `${headerHeight}px`
    );
  }
  recalcLayout();
  window.addEventListener("resize", recalcLayout);
});

const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach((item) => {
  const question = item.querySelector(".faq-question");

  question.addEventListener("click", () => {
    const isActive = item.classList.contains("active");

    faqItems.forEach((otherItem) => {
      otherItem.classList.remove("active");
    });

    if (!isActive) {
      item.classList.add("active");
    }
  });
});

const burgerBtn = document.getElementById("burgerBtn");
const mobileMenu = document.getElementById("mobileMenu");
const mobileLinks = document.querySelectorAll(".mobile-link");

if (burgerBtn && mobileMenu) {
  burgerBtn.addEventListener("click", () => {
    burgerBtn.classList.toggle("open");
    mobileMenu.classList.toggle("open");

    if (mobileMenu.classList.contains("open")) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
  });

  mobileLinks.forEach((link) => {
    link.addEventListener("click", () => {
      burgerBtn.classList.remove("open");
      mobileMenu.classList.remove("open");
      document.body.style.overflow = "";
    });
  });
}
