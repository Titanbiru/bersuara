document.getElementById('profile_picture').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('new-profile-pic-preview');

    // Check if a file was selected
    if (file) {
        console.log("File selected:", file.name);

        // Create a FileReader to read the file
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log("File read successfully");
            preview.src = e.target.result;  // Set the preview image source
            preview.style.display = 'block';  // Make the preview visible
        };
        reader.onerror = function() {
            console.log("Error reading file");
        };
        reader.readAsDataURL(file);  // Read the file as Data URL
    } else {
        console.log("No file selected");
        preview.src = '#';  // Reset preview source
        preview.style.display = 'none';  // Hide preview if no file
    }
});