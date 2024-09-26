<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location:index.php');
}

// Determine the current page
$current_page = basename($_SERVER['PHP_SELF']);
$from_dashboard = isset($_GET['from']) && $_GET['from'] === 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>welcome page</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Add your custom CSS styles here */
        .slider .menu-links .nav-link.active a {
            background-color: #b56576; /* Background color for active link */
            color: #fff; /* Text color for active link */
        }

        .slider .menu-links .nav-link.active a .icon,
        .slider .menu-links .nav-link.active a .text {
            color: #fff; /* Text color for active link */
        }
    </style>
</head>

<body>

    <nav class="slider close">
        <header>
            <div class="logo-section">
                <span class="image">
                    <img src="adlogo.png" alt="logo">
                </span>
                <div class="text logo-text">
                    <span class="heading">welcome</span>
                    <span class="sub-heading"><?php echo "  $_SESSION[username]"; ?></span>
                </div>
            </div>
            <i class="fa-solid fa-caret-right toggle"></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link <?php if ($current_page == 'dashboard.php' || $from_dashboard) echo 'active'; ?>">
                        <a href="dashboard.php">
                            <i class="fa-solid fa-house icon"></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link <?php if ($current_page == 'umar.php') echo 'active'; ?>">
                        <a href="umar.php">
                            <i class="fa-solid fa-user-plus icon"></i>
                            <span class="text nav-text">Allocate Halls</span>
                        </a>
                    </li>
                    <li class="nav-link <?php if ($current_page == 'view_allocate_halls.php') echo 'active'; ?>">
                        <a href="view_allocate_halls.php">
                            <i class="fa-solid fa-users-viewfinder icon"></i>
                            <span class="text nav-text">View Allocated Halls</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="logout.php" method="post">
                            <i class="fa-solid fa-right-from-bracket icon"></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

  
    <script>
        const body = document.querySelector('body');
        sidebar = body.querySelector('nav');
        toggle = body.querySelector('.toggle');
        toggling = body.querySelector('.toggling');
        sidebar = body.querySelector('nav');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle("close");
        })
    </script>
</body>

</html>
