<?php
    include_once('../includes/init.php');
    include_once('../database/db_categories.php');
    include_once('../database/db_pet.php');
    include_once('../templates/pets/tpl_list_pets.php');
    setLastPage($_SERVER['REQUEST_URI']);

    drawHeader();?>
    
<section class="pets" id = "myPets">
    <h2>Pets I found</h2>

    <div id="pets_i_found">
        <?php // If the user is not logged in show error message
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            drawInactiveSession('see your favourites');          
        }
        else{ 
            $myPets = getMyPets($_SESSION['username']); 
            drawPets($myPets);
        } ?>
    </div>
</section>

<?php drawFooter(); ?>
