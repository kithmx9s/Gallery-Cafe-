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

// Fade-in animation on scroll for Our Story
window.addEventListener("scroll", () => {
  const story = document.querySelector(".story-container");
  const position = story.getBoundingClientRect().top;
  const windowHeight = window.innerHeight;

  if (position < windowHeight - 100) {
    story.querySelector(".story-img").style.opacity = "1";
    story.querySelector(".story-content").style.opacity = "1";
  }
});

