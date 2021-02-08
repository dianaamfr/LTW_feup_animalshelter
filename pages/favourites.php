<?php
    include_once('../includes/init.php');
    include_once('../templates/pets/tpl_list_pets.php');
    include_once('../database/db_pet.php');  
    
    setLastPage($_SERVER['REQUEST_URI']);

    drawHeader();

?>

<section class="pets" id = "favourites">
    <h2>My Favourites</h2>

    <div id="favourite_pets">
    </div>
</section>

<?php  
    drawFooter();
?>
