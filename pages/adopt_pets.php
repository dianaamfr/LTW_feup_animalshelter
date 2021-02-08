<?php
    include_once('../includes/init.php');
    include_once('../database/db_categories.php');
    include_once('../database/db_pet.php');  
    include_once('../templates/pets/tpl_list_pets.php'); 
    include_once('../templates/pets/tpl_search_pets.php');  

    setLastPage($_SERVER['REQUEST_URI']);

    drawHeader();

    $allSpecies = getAllSpecies();
    $colors = getAllColors();
    $sizes = getAllSizes();
    $genders = getAllGenders();

    drawSearchFilters($allSpecies, $colors, $sizes, $genders);

    $allPets = getAllPets();?>

    <div class="pets" id="filteredPets">
        
    </div>
    
<?php
    drawFooter();
?>