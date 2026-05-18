document.addEventListener("DOMContentLoaded", () => {
  const revealItems = document.querySelectorAll("[data-ry-reveal]");

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
        }
      });
    },
    { threshold: 0.12 }
  );

  revealItems.forEach(item => observer.observe(item));
});
