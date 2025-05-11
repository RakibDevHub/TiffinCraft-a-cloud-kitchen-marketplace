document.addEventListener("DOMContentLoaded", function () {
  // CTA Bar functionality (only on home page for guests)
  const ctaBar = document.getElementById("ctaBar");
  if (ctaBar) {
    const closeCta = document.getElementById("closeCta");
    const mainNav = document.getElementById("mainNav");

    let ctaClosed = localStorage.getItem("ctaClosed") === "true";
    let ctaVisible = false;

    const showCTA = () => {
      if (ctaClosed) return;
      ctaBar.classList.remove("-translate-y-full", "opacity-0");
      ctaBar.classList.add("translate-y-0", "opacity-100");
      mainNav.style.top = `${ctaBar.offsetHeight}px`;
      ctaVisible = true;
    };

    const hideCTA = () => {
      ctaBar.classList.add("-translate-y-full", "opacity-0");
      ctaBar.classList.remove("translate-y-0", "opacity-100");
      mainNav.style.top = "0";
      ctaVisible = false;
    };

    window.addEventListener("scroll", () => {
      if (window.scrollY > 50 && !ctaClosed && !ctaVisible) {
        showCTA();
      } else if (window.scrollY <= 50 && !ctaClosed && ctaVisible) {
        hideCTA();
      }
    });

    closeCta.addEventListener("click", () => {
      ctaClosed = true;
      localStorage.setItem("ctaClosed", "true");
      hideCTA();
    });

    if (!ctaClosed && window.scrollY > 50) {
      showCTA();
    }
  }
});
