<nav class="sidebar locked">
    <div class="logo_items flex">
        <span class="nav_image">
            <img src="CN.jpg" alt="logo_img" />
        </span>
        <span class="logo_name">Bersuara</span>
        <i class="bx bx-lock-alt" id="lock-icon" title="Unlock Sidebar"></i>
        <i class="bx bx-x" id="sidebar-close"></i>
    </div>

        <div class="menu_container">
            <div class="menu_items">
                <ul class="menu_item">
                <div class="menu_title flex">
                    <span class="title">Menu</span>
                    <span class="line"></span>
                </div>
                <li class="item">
                    <a href="dashboard.php" class="link flex">
                        <i class="bx bx-home-alt"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="item">
                    <a href="profile.php" class="link flex">
                        <i class='bx bxs-user-rectangle'></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="item">
                    <a href="edit_profile.php" class="link flex">
                    <i class='bx bx-edit-alt'></i>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li class="item">
                    <a href="logout.php" class="link flex">
                        <i class="bx bx-log-out"></i>
                        <span>Log out</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar_profile flex">
            <span class="nav_image">
                <?php if (!empty($user['profile_picture'])): ?>
                    <!-- Display the uploaded profile picture -->
                    <img src="<?php echo './uploads/' . htmlspecialchars($user['profile_picture']); ?>" alt="Profile Image" />
                <?php else: ?>
                    <!-- Default placeholder with the first letter of the name -->
                    <div class="profile-letter">
                        <?php 
                            // Get the first letter from full_name or username
                            $firstLetter = !empty($user['full_name']) ? $user['full_name'][0] : $user['username'][0]; 
                            echo strtoupper(htmlspecialchars($firstLetter)); 
                        ?>
                    </div>
                <?php endif; ?>
            </span>
            <div class="data_text">
                <?php 
                    if (!empty($user['full_name'])) {
                        echo htmlspecialchars($user['full_name']);
                    } else {
                        echo htmlspecialchars($user['username']);
                    } 
                ?>
            </div>
        </div>
    </div>
</nav>

<nav class="navbar flex">
    <i class="bx bx-menu" id="sidebar-open"></i>
    <span><h1><b>Bersuara</b></h1></span>
    <span class="nav_image">
        <img src="cn bersuara.jpg" alt="logo_img" />
    </span>
</nav>
