// Mendapatkan elemen-elemen
const mediaInput = document.getElementById("media");
const removeButton = document.getElementById("removeButton");
const imagePreview = document.getElementById("imagePreview");
const videoPreview = document.getElementById("videoPreview");
const audioPreview = document.getElementById("audioPreview");

// Fungsi untuk mengatur tampilan awal
function initialize() {
    removeButton.style.display = "none"; // Sembunyikan tombol remove saat halaman dimuat
    imagePreview.style.display = "none";
    videoPreview.style.display = "none";
    audioPreview.style.display = "none";
}

// Tampilkan tombol remove saat file dipilih
mediaInput.addEventListener("change", function() {
    const file = mediaInput.files[0];
    if (file) {
        removeButton.style.display = "inline"; // Tampilkan tombol remove

        // Cek tipe file untuk pratinjau yang sesuai
        if (file.type.startsWith("image/")) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = "block";
            videoPreview.style.display = "none";
            audioPreview.style.display = "none";
        } else if (file.type.startsWith("video/")) {
            videoPreview.src = URL.createObjectURL(file);
            videoPreview.style.display = "block";
            imagePreview.style.display = "none";
            audioPreview.style.display = "none";
        } else if (file.type.startsWith("audio/")) {
            audioPreview.src = URL.createObjectURL(file);
            audioPreview.style.display = "block";
            imagePreview.style.display = "none";
            videoPreview.style.display = "none";
        }
    }
});

// Fungsi menghapus pratinjau dan menyembunyikan tombol remove
removeButton.addEventListener("click", function() {
    mediaInput.value = ""; // Hapus file dari input
    removeButton.style.display = "none"; // Sembunyikan tombol remove

    // Reset pratinjau file
    imagePreview.src = "";
    imagePreview.style.display = "none";
    videoPreview.src = "";
    videoPreview.style.display = "none";
    audioPreview.src = "";
    audioPreview.style.display = "none";
});

// Panggil fungsi inisialisasi saat halaman dimuat
initialize();