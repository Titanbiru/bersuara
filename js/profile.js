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
function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Menangani klik tombol delete
        const deleteButtons = document.querySelectorAll('.delete-btn');
    
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                
                // Konfirmasi penghapusan
                const confirmation = confirm('Are you sure you want to delete this post?');
                if (!confirmation) return;
    
                // Mengirim permintaan AJAX ke delete_post.php
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../delete-post-pfp.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Jika penghapusan berhasil, hapus elemen post dari tampilan
                        const postElement = document.getElementById('post-' + postId);
                        if (postElement) {
                            postElement.remove();
                        }
                        alert('Post deleted successfully.');
                    } else {
                        alert('Failed to delete post.');
                    }
                };
                xhr.send('post_id=' + postId);
            });
        });
    });
    