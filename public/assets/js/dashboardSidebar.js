// assets/js/dashboardSidebar.js
document.addEventListener("DOMContentLoaded", function () {
  // Sidebar elements
  const sidebar = document.getElementById("dashboard-sidebar");
  const toggleButton = document.getElementById("sidebar-toggle");
  const toggleIcon = document.getElementById("toggle-icon");
  const sidebarTexts = document.querySelectorAll(".sidebar-text");
  const logo = document.getElementById("sidebar-logo");

  // Mobile elements
  const mobileToggle = document.getElementById("mobile-sidebar-toggle");
  const mobileSidebar = document.getElementById("mobile-sidebar-overlay");
  const mobileBackdrop = document.getElementById("mobile-sidebar-backdrop");
  const mobileClose = document.getElementById("mobile-sidebar-close");

  // Main content
  const mainContent = document.getElementById("main-content");

  // Check localStorage for collapsed state
  let isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";

  // Initialize sidebar state
  function initSidebar() {
    if (isCollapsed) {
      collapseSidebar();
    } else {
      expandSidebar();
    }
  }

  // Collapse sidebar to icons only
  function collapseSidebar() {
    sidebar.style.width = "5rem";
    sidebarTexts.forEach((text) => (text.style.display = "none"));
    logo.querySelector("img").classList.remove("mr-2");
    toggleIcon.classList.remove("fa-chevron-left");
    toggleIcon.classList.add("fa-chevron-right");

    // Adjust menu item padding
    document.querySelectorAll("#dashboard-sidebar a").forEach((link) => {
      link.classList.remove("justify-start");
      link.classList.add("justify-center");
      link.querySelector("i").classList.remove("mr-3");
    });

    // Adjust main content
    if (mainContent) {
      mainContent.style.marginLeft = "5rem";
    }

    // Update state
    isCollapsed = true;
    localStorage.setItem("sidebarCollapsed", "true");
  }

  // Expand sidebar to full width
  function expandSidebar() {
    sidebar.style.width = "16rem";
    sidebarTexts.forEach((text) => (text.style.display = "inline"));
    logo.querySelector("img").classList.add("mr-2");
    toggleIcon.classList.remove("fa-chevron-right");
    toggleIcon.classList.add("fa-chevron-left");

    // Adjust menu item padding
    document.querySelectorAll("#dashboard-sidebar a").forEach((link) => {
      link.classList.remove("justify-center");
      link.classList.add("justify-start");
      link.querySelector("i").classList.add("mr-3");
    });

    // Adjust main content
    if (mainContent) {
      mainContent.style.marginLeft = "16rem";
    }

    // Update state
    isCollapsed = false;
    localStorage.setItem("sidebarCollapsed", "false");
  }

  // Toggle sidebar state
  function toggleSidebar() {
    if (isCollapsed) {
      expandSidebar();
    } else {
      collapseSidebar();
    }
  }

  // Mobile sidebar functions
  function showMobileSidebar() {
    mobileSidebar.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }

  function hideMobileSidebar() {
    mobileSidebar.classList.add("hidden");
    document.body.style.overflow = "";
  }

  // Event listeners
  if (toggleButton) {
    toggleButton.addEventListener("click", toggleSidebar);
  }

  if (mobileToggle) {
    mobileToggle.addEventListener("click", showMobileSidebar);
  }

  if (mobileBackdrop) {
    mobileBackdrop.addEventListener("click", hideMobileSidebar);
  }

  if (mobileClose) {
    mobileClose.addEventListener("click", hideMobileSidebar);
  }

  // Initialize
  initSidebar();
});
