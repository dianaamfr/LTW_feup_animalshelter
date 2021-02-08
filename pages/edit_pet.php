<?php
    include_once('../includes/init.php');
    include_once('../database/db_categories.php');
    include_once('../templates/pets/tpl_edit_pet.php');
    include_once('../database/db_pet.php');  
    
    setLastPage($_SERVER['REQUEST_URI']);

    if (!isset($_GET['id'])){
        die(header('Location: ../pages/pets_i_found.php'));
    }

    $pet = getPetById($_GET['id']);
    if(empty($pet) ||! isAvailable($_GET['id'])){
        die(header('Location: ../pages/pets_i_found.php'));
    }

    drawHeader();

    $allSpecies = getAllSpecies();
    $colors = getAllColors();
    $sizes = getAllSizes();
    $genders = getAllGenders();
    $actualNumberOfImages = getNumberOfImages($pet['petId']);

    drawEditPet($allSpecies, $colors, $sizes, $genders, $pet, $actualNumberOfImages);

    drawFooter();
?>