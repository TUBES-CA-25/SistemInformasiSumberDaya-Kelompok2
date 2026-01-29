document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("adminSidebar");
  const toggleBtn = document.getElementById("sidebarToggle");
  const closeBtn = document.getElementById("closeSidebar");
  const overlay = document.getElementById("sidebarOverlay");

  function toggleSidebar() {
    if (window.innerWidth < 768) {
      // Mobile: Slide in/out
      if (sidebar.classList.contains("-translate-x-full")) {
        sidebar.classList.remove("-translate-x-full");
        sidebar.classList.add("translate-x-0");
        overlay.classList.remove("hidden");
        document.body.style.overflow = "hidden";
      } else {
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0");
        overlay.classList.add("hidden");
        document.body.style.overflow = "";
      }
    } else {
      // Desktop: Collapse/Expand
      sidebar.classList.toggle("sidebar-collapsed");
    }
  }

  if (toggleBtn) toggleBtn.addEventListener("click", toggleSidebar);
  if (closeBtn) closeBtn.addEventListener("click", toggleSidebar);
  if (overlay) overlay.addEventListener("click", toggleSidebar);

  // active links handling...
  const activeLinks = document.querySelectorAll('a[data-active="true"]');

  activeLinks.forEach((link) => {
    const parentMenu = link.closest("ul");
    if (parentMenu) {
      parentMenu.classList.remove("hidden");
      parentMenu.classList.add("block");

      let arrowId = "";
      if (parentMenu.id === "menuMaster") arrowId = "arrowMaster";
      if (parentMenu.id === "menuOps") arrowId = "arrowOps";

      const arrow = document.getElementById(arrowId);
      if (arrow) {
        arrow.classList.add("rotate-90");
        arrow.classList.add("text-white");
        arrow.classList.remove("text-slate-600");
      }
    }
  });
});

function toggleMenu(menuId, arrowId) {
  const menu = document.getElementById(menuId);
  const arrow = document.getElementById(arrowId);

  if (menu.classList.contains("hidden")) {
    menu.classList.remove("hidden");
    menu.classList.add("block");
    if (arrow) {
      arrow.classList.add("rotate-90");
      arrow.classList.remove("text-slate-600");
      arrow.classList.add("text-white");
    }
  } else {
    menu.classList.add("hidden");
    menu.classList.remove("block");
    if (arrow) {
      arrow.classList.remove("rotate-90");
      arrow.classList.add("text-slate-600");
      arrow.classList.remove("text-white");
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const activeLinks = document.querySelectorAll('a[data-active="true"]');

  activeLinks.forEach((link) => {
    const parentMenu = link.closest("ul");
    if (parentMenu) {
      parentMenu.classList.remove("hidden");
      parentMenu.classList.add("block");

      let arrowId = "";
      if (parentMenu.id === "menuMaster") arrowId = "arrowMaster";
      if (parentMenu.id === "menuOps") arrowId = "arrowOps";

      const arrow = document.getElementById(arrowId);
      if (arrow) {
        arrow.classList.add("rotate-90");
        arrow.classList.add("text-white");
        arrow.classList.remove("text-slate-600");
      }
    }
  });
});
