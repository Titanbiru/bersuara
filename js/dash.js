    // Selecting the sidebar and buttons
    const sidebar = document.querySelector(".sidebar");
    const sidebarOpenBtn = document.querySelector("#sidebar-open");
    const sidebarCloseBtn = document.querySelector("#sidebar-close");
    const sidebarLockBtn = document.querySelector("#lock-icon");
    
    // Function to toggle the lock state of the sidebar
    const toggleLock = () => {
      sidebar.classList.toggle("locked");
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-alt", "bx-lock-open-alt");
      } else {
        sidebar.classList.add("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      }
    };
    
    // Function to show and hide the sidebar
    const toggleSidebar = () => {
      sidebar.classList.toggle("close");
    };
    
    // Function to open the sidebar on hover (on mouseover)
    const openSidebarOnHover = () => {
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.remove("close"); // Open the sidebar when hovered
      }
    };
    
    // Function to close the sidebar when the mouse leaves (on mouseout)
    const closeSidebarOnLeave = () => {
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.add("close"); // Close the sidebar when mouse leaves
      }
    };
    
    // Initial check for window width when the page loads
    const checkSidebarLock = () => {
      if (window.innerWidth >= 800) {
        // For desktop: Sidebar should be locked and closed
        sidebar.classList.add("locked", "close"); // Sidebar closed and locked by default
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      } else {
        // For mobile: Sidebar should be locked and closed initially
        sidebar.classList.add("locked", "close"); // Sidebar closed and locked by default
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      }
    };
    
    // Run the function to check sidebar lock status
    checkSidebarLock();
    
    // Adding event listeners to buttons and sidebar for the corresponding actions
    sidebarLockBtn.addEventListener("click", toggleLock);
    sidebarOpenBtn.addEventListener("click", toggleSidebar);
    sidebarCloseBtn.addEventListener("click", toggleSidebar);
    
    // Adding hover behavior to the sidebar
    sidebar.addEventListener("mouseover", openSidebarOnHover);  // Open sidebar when mouse enters
    sidebar.addEventListener("mouseout", closeSidebarOnLeave);  // Close sidebar when mouse leaves
    
    // Ensure the sidebar state updates when window is resized
    window.addEventListener("resize", checkSidebarLock);
    
    // Make sure the sidebar is hidden when page loads if in mobile view
    document.addEventListener("DOMContentLoaded", checkSidebarLock);
    