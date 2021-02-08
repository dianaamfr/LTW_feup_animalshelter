<?php
/**
 * Draw Header
 */
function drawHeader(){ ?>
    <!DOCTYPE html> 
    <html lang="en">
        <head>
            <title>Animal Shelter</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="stylesheet" href="../css/responsive.css">
            <script src="https://kit.fontawesome.com/e082488467.js" crossorigin="anonymous"></script>
            <script src="../javascript/auth_script.js" defer></script>
            <script src="../javascript/script.js" defer></script>
        </head>
        <body>
            <header>
                <div id="menu-top">
                    <span id="social">
                        <a href="#" class="fa fa-whatsapp"></a>
                        <a href="#" class="fa fa-instagram"></a>
                        <a href="#" class="fa fa-facebook"></a>
                    </span>
                    <nav id="desktop_session_menu">
                        <ul>
                        
                            <?php // If the user is authenticated
                            if (isset($_SESSION['username']) && ($_SESSION['username'] != '')) { ?>
                                <li><p>Welcome <?=$_SESSION['username']?></p></li>
                                <li><a href="../pages/edit_profile.php">Edit Profile</a></li>
                                <li id="logout_btn">
                                    <form action="../actions/action_logout.php" method="post">
                                        <input type="submit" value="Sign Out">
                                    </form>
                                </li>
                                <li><a href="../pages/messages.php"><i class="fas fa-envelope"></i></a></li>

                            <?php } // If the user is not authenticated
                            else { ?>
                                <li><a href="../pages/register.php">Register</a></li>
                                <li><a href="../pages/login.php">Sign In</a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <div id="menu-bar">
                    <div id="page_title">
                        <i class="fa fa-paw"></i>
                        <a href="../pages/home.php">
                            <h1>Animal<br>Shelter</h1>
                        </a>
                    </div>
                    <nav id="desktop_menu">
                        <ul>

                            <li>
                                <a href="#">Proposals</a>
                                <?php if (isset($_SESSION['username']) && ($_SESSION['username'] != '')) { ?>
                                    <span class="proposals_pending"></span>
                                <?php } ?>
                                <ul>
                                    <li><a href="../pages/my_proposals.php">My Proposals</a></li>
                                    <li><a href="../pages/received_proposals.php">Received Proposals</a></li>
                                </ul>
                                </li>
                            <li><a href="../pages/adopt_pets.php">Adopt a pet</a></li>
                            <li><a href="../pages/favourites.php">Favourites</a></li>
                            <li><a href="../pages/found_pet.php">Found a pet</a></li>

                            <li><a href="../pages/pets_i_found.php">Pets I found</a></li>
                        </ul>
                    </nav>
                    <nav id="responsive_menu">
                        <i id="responsive_menu_icon" class="fas fa-bars"></i>
                        <ul>
                            <li><a href="../pages/my_proposals.php">My Proposals</a></li>
                            <li>
                                <a href="../pages/received_proposals.php">Received Proposals</a>
                                <?php if (isset($_SESSION['username']) && ($_SESSION['username'] != '')) { ?>
                                    <span class="proposals_pending"></span>
                                <?php } ?>
                            </li>
                            <li><a href="../pages/adopt_pets.php">Adopt a pet</a></li>
                            <li><a href="../pages/favourites.php">Favourites</a></li>
                            <li><a href="../pages/found_pet.php">Found a pet</a></li>
                            <li><a href="../pages/pets_i_found.php">Pets I found</a></li>
                        </ul>
                    </nav>
                </div>
            </header>
<?php } ?>

<?php

/**
 * Draw Footer
 */
function drawFooter(){ ?>
            <footer>
                <p>LTW 2020 - G64</p>
            </footer>
        </body>
    </html>
<?php } ?>
