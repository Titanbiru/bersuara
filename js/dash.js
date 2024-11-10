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
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      } else {
        sidebar.classList.add("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-alt", "bx-lock-open-alt");
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

    
document.querySelectorAll('.reply-btn').forEach(button => {
  button.addEventListener('click', function() {
      const commentId = this.getAttribute('data-comment-id');
      const replyForm = document.getElementById(`reply-form-${commentId}`);
      replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
  });
});

document.getElementById("shareButton").addEventListener("click", async () => {
  const shareData = {
      title: 'Judul Postingan',
      text: 'Ini adalah isi dari postingan yang akan dibagikan',
      url: window.location.href // URL halaman saat ini atau URL dari posting
  };

  try {
      await navigator.share(shareData);
      console.log("Berbagi berhasil");
  } catch (error) {
      console.log("Berbagi gagal:", error);
  }
});
    $('.like-btn, .dislike-btn').click(function() {
      var postId = $(this).closest('.post').data('post-id');
      var action = $(this).data('action'); // 'like' atau 'dislike'
  
      $.ajax({
          url: '../dashboard.php', // File PHP untuk memproses
          type: 'POST',
          data: { post_id: postId, action: action },
          success: function(response) {
              var data = JSON.parse(response);
              $('.like-count').text(data.likeCount);
              $('.dislike-count').text(data.dislikeCount);
          }
      });
  });

  $('.comment-form').submit(function(e) {
    e.preventDefault();

    var postId = $(this).closest('.post').data('post-id');
    var commentText = $(this).find('textarea').val();

    $.ajax({
        url: '../process-cmd-rply.php',
        type: 'POST',
        data: { post_id: postId, comment: commentText },
        success: function(response) {
            var data = JSON.parse(response);
            // Menambahkan komentar baru ke bagian komentar
            $('.comments').prepend('<p>' + data.username + ': ' + data.comment + '</p>');
            // Kosongkan textarea setelah kirim
            $(this).find('textarea').val('');
        }
    });
});

$('.reply-btn').click(function() {
  $(this).siblings('.reply-form').toggle(); // Menampilkan form balasan
});

$('.reply-form').submit(function(e) {
  e.preventDefault();

  var commentId = $(this).closest('.comment').data('comment-id');
  var replyText = $(this).find('textarea').val();

  $.ajax({
      url: '../process-cmd-rply.php',
      type: 'POST',
      data: { comment_id: commentId, reply: replyText },
      success: function(response) {
          var data = JSON.parse(response);
          // Menambahkan balasan baru
          $('.replies').prepend('<p>' + data.username + ': ' + data.reply + '</p>');
          $(this).find('textarea').val(''); // Kosongkan textarea setelah kirim
      }
  });
});
