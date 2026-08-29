const hamburger = document.getElementById("hamburger");
const nav = document.getElementById("nav");

if (hamburger && nav) {
  const toggleNav = () => {
    hamburger.classList.toggle("is-open");
    nav.classList.toggle("is-open");
    document.body.classList.toggle("is-fixed");
    const isOpen = hamburger.classList.contains("is-open");
    hamburger.setAttribute("aria-expanded", isOpen);
  };

  hamburger.addEventListener("click", toggleNav);

  nav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      if (nav.classList.contains("is-open")) {
        toggleNav();
      }
    });
  });
}