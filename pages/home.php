<?php
    include_once('../includes/init.php');
    include_once('../templates/pets/tpl_list_pets.php');
    include_once('../templates/pets/tpl_banner.php');
    include_once('../database/db_pet.php');
    setLastPage($_SERVER['REQUEST_URI']);
    
    drawHeader();
    drawBanner();

    $first_pets = getFirstPets();
    drawPetsPreview($first_pets);

    drawFooter();
?>