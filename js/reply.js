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
