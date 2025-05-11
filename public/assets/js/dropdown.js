// assets/js/dropdown.js
class Dropdown {
  constructor(buttonId, menuId) {
    this.button = document.getElementById(buttonId);
    this.menu = document.getElementById(menuId);

    if (this.button && this.menu) {
      this.init();
    }
  }

  init() {
    this.button.addEventListener("click", (e) => this.toggleDropdown(e));
    document.addEventListener("click", (e) => this.handleOutsideClick(e));
    document.addEventListener("keydown", (e) => this.handleKeyboard(e));
  }

  toggleDropdown(e) {
    e.stopPropagation();
    const isExpanded = this.button.getAttribute("aria-expanded") === "true";
    this.button.setAttribute("aria-expanded", !isExpanded);
    this.menu.classList.toggle("hidden");
  }

  handleOutsideClick(e) {
    if (!this.button.contains(e.target) && !this.menu.contains(e.target)) {
      this.closeDropdown();
    }
  }

  handleKeyboard(e) {
    if (e.key === "Escape") {
      this.closeDropdown();
    }
  }

  closeDropdown() {
    this.button.setAttribute("aria-expanded", "false");
    this.menu.classList.add("hidden");
  }
}

// Initialize dropdowns when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // User profile dropdown
  new Dropdown("user-menu-button", "user-dropdown");

  // Mobile menu dropdown
  new Dropdown("mobile-menu-button", "mobile-menu");
});
