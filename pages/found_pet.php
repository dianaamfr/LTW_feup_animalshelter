<?php
    include_once('../includes/init.php');
    include_once('../database/db_categories.php');
    include_once('../templates/pets/tpl_add_pet.php');
    setLastPage($_SERVER['REQUEST_URI']);

    drawHeader();

    $allSpecies = getAllSpecies();
    $colors = getAllColors();
    $sizes = getAllSizes();
    $genders = getAllGenders();

    drawAddPet($allSpecies, $colors, $sizes, $genders);

    drawFooter();
?>