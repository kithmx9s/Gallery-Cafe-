// Mobile menu toggle
const menuToggle = document.getElementById("menu-toggle");
const navLinks = document.querySelector(".nav-links");

menuToggle.addEventListener("click", () => {
  navLinks.classList.toggle("active");
  const icon = menuToggle.querySelector("i");

  // toggle menu icon
  if (navLinks.classList.contains("active")) {
    icon.classList.replace("fa-bars", "fa-xmark");
  } else {
    icon.classList.replace("fa-xmark", "fa-bars");
  }
});

// Scroll reveal for hero
window.addEventListener("scroll", () => {
  const hero = document.querySelector(".hero-content");
  const scrollPos = window.scrollY;

  if (scrollPos > 50) {
    hero.style.opacity = "3";
    hero.style.transform = "translateY(0)";
  }
});
