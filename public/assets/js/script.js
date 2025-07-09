document.addEventListener("DOMContentLoaded", () => {
  // Reset CTA on reload
  if (performance.getEntriesByType("navigation")[0].type === "reload") {
    localStorage.removeItem("ctaClosed");
  }

  initCTABar();
  initSidebar();
  initDropdowns();
  initSmartPageScroll();
  initSmoothAnchorOffset();
});

// CTA BAR LOGIC
function initCTABar() {
  const ctaBar = document.getElementById("ctaBar");
  const closeCta = document.getElementById("closeCta");
  const mainNav = document.getElementById("mainNav");
  const sidebar = document.getElementById("sidebar-navlinks");
  const sidebarOverlay = document.getElementById("sidebar-navlinks-overlay");

  if (!ctaBar) return;

  let ctaClosed = localStorage.getItem("ctaClosed") === "true";
  let ctaVisible = false;
  const ctaHeight = ctaBar.offsetHeight;

  const showCTA = () => {
    if (ctaClosed) return;
    ctaBar.classList.remove("-translate-y-full", "opacity-0");
    ctaBar.classList.add("translate-y-0", "opacity-100");
    mainNav.style.top = `${ctaHeight}px`;

    if (sidebar) {
      const offset = ctaHeight + mainNav.offsetHeight;
      sidebar.style.top = `${offset}px`;
      sidebarOverlay.style.top = `${offset}px`;
    }

    ctaVisible = true;
  };

  const hideCTA = () => {
    ctaBar.classList.add("-translate-y-full", "opacity-0");
    ctaBar.classList.remove("translate-y-0", "opacity-100");
    mainNav.style.top = "0";

    if (sidebar) {
      sidebar.style.top = `${mainNav.offsetHeight}px`;
      sidebarOverlay.style.top = `${mainNav.offsetHeight}px`;
    }

    ctaVisible = false;
  };

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50 && !ctaClosed && !ctaVisible) showCTA();
    else if (window.scrollY <= 50 && !ctaClosed && ctaVisible) hideCTA();
  });

  closeCta?.addEventListener("click", () => {
    ctaClosed = true;
    localStorage.setItem("ctaClosed", "true");
    hideCTA();
  });

  if (!ctaClosed && window.scrollY > 50) {
    showCTA();
  }
}

// SIDEBAR LOGIC
let sidebarBtn,
  sidebarMenu,
  sidebarOverlay,
  sidebarOpenIcon,
  sidebarCloseIcon,
  sidebarContainer;

function closeSidebar() {
  if (!sidebarMenu || !sidebarBtn) return;

  sidebarBtn.setAttribute("aria-expanded", "false");
  sidebarOpenIcon?.classList.remove("hidden");
  sidebarCloseIcon?.classList.add("hidden");
  sidebarOverlay?.classList.add("opacity-0");
  sidebarContainer?.classList.add("-translate-x-full");

  setTimeout(() => {
    sidebarMenu.classList.add("hidden");
    document.body.style.overflow = "";
  }, 300);
}

function initSidebar() {
  sidebarBtn = document.getElementById("sidebar-navlinks-button");
  sidebarMenu = document.getElementById("sidebar-navlinks");
  sidebarOverlay = document.getElementById("sidebar-navlinks-overlay");
  sidebarOpenIcon = document.getElementById("sidebar-navlinks-open-icon");
  sidebarCloseIcon = document.getElementById("sidebar-navlinks-close-icon");
  sidebarContainer = sidebarMenu?.querySelector("div:last-child");

  if (!sidebarBtn || !sidebarMenu) return;

  const isDashboard =
    window.location.pathname.includes("/admin") ||
    window.location.pathname.includes("/dashboard");

  if (isDashboard) sidebarMenu.classList.remove("md:hidden");

  function openSidebar() {
    closeAllDropdowns();
    sidebarBtn.setAttribute("aria-expanded", "true");
    sidebarOpenIcon?.classList.add("hidden");
    sidebarCloseIcon?.classList.remove("hidden");
    sidebarMenu.classList.remove("hidden");

    void sidebarMenu.offsetWidth;
    sidebarOverlay?.classList.remove("opacity-0");
    sidebarContainer?.classList.remove("-translate-x-full");

    document.body.style.overflow = "hidden";
  }

  sidebarBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    const expanded = sidebarBtn.getAttribute("aria-expanded") === "true";
    expanded ? closeSidebar() : openSidebar();
  });

  sidebarOverlay?.addEventListener("click", closeSidebar);

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeSidebar();
  });
}

// DROPDOWNS LOGIC
let dropdowns = [];

function initDropdowns() {
  dropdowns = [
    {
      button: document.getElementById("user-dropdown-button"),
      menu: document.getElementById("user-dropdown"),
    },
    {
      button: document.getElementById("notification-button"),
      menu: document.getElementById("notification-dropdown"),
    },
  ];

  dropdowns.forEach(({ button, menu }) => {
    if (!button || !menu) return;

    let isOpen = false;

    const show = () => {
      closeAllDropdowns();
      closeSidebar();
      menu.classList.remove("hidden", "opacity-0", "scale-95");
      menu.classList.add("opacity-100", "scale-100");
      button.setAttribute("aria-expanded", "true");
      isOpen = true;
    };

    const hide = () => {
      menu.classList.add("opacity-0", "scale-95");
      menu.classList.remove("opacity-100", "scale-100");
      button.setAttribute("aria-expanded", "false");
      setTimeout(() => menu.classList.add("hidden"), 200);
      isOpen = false;
    };

    button.addEventListener("click", (e) => {
      e.stopPropagation();
      isOpen ? hide() : show();
    });

    button._dropdownState = { isOpen: () => isOpen, hide };
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest(".dropdown-container")) {
      closeAllDropdowns();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeAllDropdowns();
    }
  });
}

function closeAllDropdowns() {
  dropdowns.forEach(({ button }) => {
    if (button?._dropdownState?.isOpen()) {
      button._dropdownState.hide();
    }
  });
}

// SCROLL POSITION LOGIC
function initSmartPageScroll() {
  document.querySelectorAll('a[href^="/"]').forEach((link) => {
    link.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      const currentPath = window.location.pathname;

      if (href === currentPath || href === currentPath + "/") {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    });
  });
}

function initSmoothAnchorOffset() {
  const OFFSET_SELECTOR = "#ctaBar";

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href").slice(1);
      const targetElement = document.getElementById(targetId);
      if (!targetElement) return;

      e.preventDefault();

      const ctaBar = document.querySelector(OFFSET_SELECTOR);
      const ctaHeight =
        ctaBar && !ctaBar.classList.contains("opacity-0")
          ? ctaBar.offsetHeight
          : 0;

      // Set custom offset buffer based on section
      let OFFSET_BUFFER = 100;
      if (targetId === "explore") {
        OFFSET_BUFFER = 50;
      }

      const scrollY =
        targetElement.getBoundingClientRect().top +
        window.pageYOffset -
        ctaHeight -
        OFFSET_BUFFER;

      window.scrollTo({
        top: scrollY,
        behavior: "smooth",
      });
    });
  });
}
