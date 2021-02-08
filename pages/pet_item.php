<?php
  include_once('../includes/init.php');
  include_once('../database/db_pet.php');  
  include_once('../templates/pets/tpl_list_pets.php');

  setLastPage($_SERVER['REQUEST_URI']); 

  // If no petId
  if (!isset($_GET['id'])){
    die("No id!");
  }

  drawHeader();

  $pet = getPetById($_GET['id']);
  $petImages = getPetImagesByPetId($_GET['id']);
?>
  
  <div id="pet_page">
    <?php drawPet($pet, $petImages) ?>
  </div>

  <?php // If the user is not logged in write a message
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){ 
    drawInactiveSession('contact/propose or edit the post');
  }

  drawMessages();  // Draw session messages
  drawFooter(); ?>