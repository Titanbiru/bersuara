// Modal untuk mengganti gambar profil
function openProfilePicModal() {
    document.getElementById("profile-pic-modal").style.display = "flex";
}

function closeProfilePicModal() {
    document.getElementById("profile-pic-modal").style.display = "none";
}

// Event listener untuk menangani klik di luar modal (untuk menutup modal)
window.onclick = function(event) {
    if (event.target === document.getElementById("profile-pic-modal")) {
        closeProfilePicModal();
    }
};

// Fungsi untuk menangani like post
function likePost(postId) {
    alert("You liked post " + postId);
    // Anda dapat mengirimkan request ke server untuk menandai post ini sebagai disukai.
}

// Fungsi untuk menangani delete post
function deletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        alert("You deleted post " + postId);
        // Add your code to send a request to the server to delete the post.
    }
}
// Function to open the profile picture modal
function openProfilePicModal() {
    document.getElementById("profile-pic-modal").style.display = "flex";
}

// Function to close the profile picture modal
function closeProfilePicModal() {
    document.getElementById("profile-pic-modal").style.display = "none";
}

// Close modal if clicked outside the modal content
window.onclick = function(event) {
    if (event.target === document.getElementById("profile-pic-modal")) {
        closeProfilePicModal();
    }
}



// Optional: you can add a delay to give a smoother effect
document.addEventListener('DOMContentLoaded', function () {
    const profilePicModal = document.getElementById('profile-pic-modal');
    const closeBtn = document.querySelector('.close');

    // Add smooth fade-in effect for modal
    profilePicModal.style.transition = 'opacity 0.5s ease';

    // Open modal with a fade-in effect
    function openModal() {
        profilePicModal.style.opacity = '1';
        profilePicModal.style.display = 'flex';
    }

    // Close modal with a fade-out effect
    function closeModal() {
        profilePicModal.style.opacity = '0';
        setTimeout(() => {
            profilePicModal.style.display = 'none';
        }, 500); // Match this timeout with the fade effect duration
    }

    closeBtn.addEventListener('click', closeModal);

    window.onclick = function(event) {
        if (event.target === profilePicModal) {
            closeModal();
        }
    }
});
function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }