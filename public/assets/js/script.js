document.addEventListener("DOMContentLoaded", function () {

  initCTABar();
  initDropdowns();
  initSidebar();

});


function initCTABar() {
  const ctaBar = document.getElementById("ctaBar");
  if (!ctaBar) return;

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

function initDropdowns() {
  // Initialize user profile dropdown
  const dropdownButton = document.getElementById("user-dropdown-button");
  if (dropdownButton) {
    startDropdown({
      button: dropdownButton,
      menuId: "user-dropdown",
      closeOnClickOutside: true,
    });
  }

  // Initialize notifications dropdown
  const notificationButton = document.getElementById("notification-button");
  if (notificationButton) {
    startDropdown({
      button: notificationButton,
      menuId: "notification-dropdown",
      closeOnClickOutside: true,
    });
  }
}

function startDropdown({ button, menuId, closeOnClickOutside }) {
  const menu = document.getElementById(menuId);
  if (!menu) return;

  let isExpanded = false;

  // Toggle menu visibility
  const toggleMenu = (e) => {
    e?.stopPropagation();
    isExpanded = !isExpanded;
    button.setAttribute("aria-expanded", isExpanded);
    updateMenuVisibility();
  };

  // Update menu visibility with animations
  const updateMenuVisibility = () => {
    if (isExpanded) {
      menu.classList.remove("hidden", "opacity-0", "scale-95");
      menu.classList.add("opacity-100", "scale-100");
      menu.querySelector("a, button")?.focus();
    } else {
      menu.classList.add("opacity-0", "scale-95");
      menu.classList.remove("opacity-100", "scale-100");
      setTimeout(() => menu.classList.add("hidden"), 200);
    }
  };

  // Handle clicks outside the dropdown
  const handleOutsideClick = (e) => {
    if (!button.contains(e.target) && !menu.contains(e.target)) {
      isExpanded = false;
      button.setAttribute("aria-expanded", "false");
      updateMenuVisibility();
    }
  };

  // Handle keyboard navigation
  const handleKeyboard = (e) => {
    if (e.key === "Escape") {
      isExpanded = false;
      button.setAttribute("aria-expanded", "false");
      updateMenuVisibility();
      button.focus();
    }
  };

  // Set up event listeners
  button.addEventListener("click", toggleMenu);
  if (closeOnClickOutside) {
    document.addEventListener("click", handleOutsideClick);
  }
  document.addEventListener("keydown", handleKeyboard);
}

document.addEventListener("DOMContentLoaded", initDropdowns);


function initSidebar() {
  const sidebarButton = document.getElementById("sidebar-navlinks-button");
  if (!sidebarButton) return;

  const menu = document.getElementById("sidebar-navlinks");
  if (!menu) return;

  const overlay = document.getElementById("sidebar-navlinks-overlay");
  const openIcon = document.getElementById("sidebar-navlinks-open-icon");
  const closeIcon = document.getElementById("sidebar-navlinks-close-icon");
  const menuContainer = menu.querySelector("div:last-child");

  let isExpanded = false;

  const isDashboardView =
    window.location.pathname.includes("/admin") ||
    window.location.pathname.includes("/dashboard");
  if (isDashboardView) {
    menu.classList.remove("md:hidden");
  }

  function toggleSidebar(e) {
    e?.stopPropagation();
    isExpanded ? closeSidebar() : openSidebar();
  }

  function openSidebar() {
    isExpanded = true;
    document.body.style.overflow = "hidden";
    sidebarButton.setAttribute("aria-expanded", "true");
    openIcon?.classList.add("hidden");
    closeIcon?.classList.remove("hidden");
    menu.classList.remove("hidden");

    void menu.offsetWidth;
    overlay?.classList.remove("opacity-0");
    menuContainer?.classList.remove("-translate-x-full");
  }

  function closeSidebar() {
    isExpanded = false;
    sidebarButton.setAttribute("aria-expanded", "false");
    openIcon?.classList.remove("hidden");
    closeIcon?.classList.add("hidden");
    overlay?.classList.add("opacity-0");
    menuContainer?.classList.add("-translate-x-full");

    setTimeout(() => {
      menu.classList.add("hidden");
      document.body.style.overflow = "";
    }, 300);
  }

  function handleKeyboard(e) {
    if (e.key === "Escape") {
      closeSidebar();
      sidebarButton.focus();
    }
  }

  sidebarButton.addEventListener("click", toggleSidebar);
  overlay?.addEventListener("click", closeSidebar);
  document.addEventListener("keydown", handleKeyboard);
}
