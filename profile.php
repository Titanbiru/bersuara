<?php
session_start();
include 'database/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT username, full_name, email, bio, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

$query = "SELECT posts.id, posts.text, posts.created_at, posts.media 
        FROM posts 
        WHERE user_id = ? 
        ORDER BY created_at DESC";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->prepare("
    SELECT COUNT(likes.id) AS total_likes
    FROM posts
    JOIN likes ON posts.id = likes.post_id
    WHERE posts.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);
$total_likes = $query->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile-6.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/profile.js" defer></script>
</head>
<body>
    <?php include 'layout/sidebar.php'?>

    <div class="profile-container">
        <div class="content">
            <h1>Your Profile</h1>
            <div class="profile-details">
            <div class="profile-pic">
                <?php
                if (!empty($user['profile_picture'])) {
                    echo '<img src="uploads/' . htmlspecialchars($user['profile_picture']) . '" alt="Profile Picture">';
                } else {
                    if (!empty($user['full_name'])) {
                        $full_name_parts = explode(' ', htmlspecialchars($user['full_name']));
                        $initial = strtoupper($full_name_parts[0][0]); 
                    } else {
                        if (!empty($user['username'])) {
                            $initial = strtoupper($user['username'][0]);
                        } else {
                            $initial = 'U';
                        }
                    }
                    echo '<div class="profile-initial">' . $initial . '</div>';
                }
                ?>
            </div>
            <div class="profile-info">
                    <h2>
                        <?php 
                            echo htmlspecialchars(!empty($user['full_name']) ? $user['full_name'] : $user['username'] ?? 'No Name Set');
                        ?>
                    </h2>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? 'No Username Set'); ?></p>
                    <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?? 'No Bio Available'); ?></p>
                    <br>
                    <p>Likes: <?php echo $total_likes ? $total_likes : 0; ?></p>
                </div>
            </div>

        </div>

        <div class="user-posts">
            <h2>Your Posts</h2>
            <!-- Query untuk menampilkan postingan pengguna -->
            <?php
            $post_query = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
            $post_statement = $pdo->prepare($post_query);
            $post_statement->execute([$user_id]);
            $posts = $post_statement->fetchAll(PDO::FETCH_ASSOC);
            
            if ($posts) {
                foreach ($posts as $post) {
                    echo "<div class='post'>";
                    echo "<p>" . htmlspecialchars($post['text']) . "</p>";
                    
                    // Cek apakah ada media video
                    if (!empty($post['media'])) {
                      // Get file extension
                      $file_extension = pathinfo($post['media'], PATHINFO_EXTENSION);
                      
                      // Display video if the file is a video
                      if (in_array($file_extension, ['mp4', 'webm', 'ogg'])) {
                          echo "<video width='320' height='240' controls>";
                          echo "<source src='uploads/" . htmlspecialchars($post['media']) . "' type='video/" . htmlspecialchars($file_extension) . "'>";
                          echo "Your browser does not support the video tag.";
                          echo "</video>";
                      }
                      // Display audio if the file is an audio
                      elseif (in_array($file_extension, ['mp3', 'wav', 'ogg'])) {
                          echo "<audio controls>";
                          echo "<source src='uploads/" . htmlspecialchars($post['media']) . "' type='audio/" . htmlspecialchars($file_extension) . "'>";
                          echo "Your browser does not support the audio tag.";
                          echo "</audio>";
                      }
                  }                  
                    echo "<span class='post-time'>" . htmlspecialchars($post['created_at']) . "</span>";
                    echo "</div>";
                }
            } else {
                echo "<p>You haven't made any posts yet.</p>";
            }
            ?>
        </div>
    </div>
</div>
<script>
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
</script>
</body>
</html>
