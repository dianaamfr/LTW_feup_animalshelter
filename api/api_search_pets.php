<?php
    include_once('../includes/init.php');
    include_once('../database/db_pet.php');
    include_once('../utils/validation.php');
  
  // If no filters were set
  if(!areFiltersSet()){
    // Show all the pets
    $pets = getAllPets();

    // Remove pets from the user and mark favourites
    $result = filterResult($pets);
    die(json_encode($result));  
  }

  // If filters were set get all filters
  $species = json_decode($_POST['species']);
  $gender = test_input($_POST['gender']);
  $sizes = json_decode($_POST['sizes']);
  list($lowerAge,$upperAge) = explode("-", $_POST['age']);
  $location = test_input($_POST['location']);
  $color = test_input($_POST['color']);
  $breed = test_input($_POST['breed']);

  if(!isInt($gender) || !isInt($color) || !isSimpleString($location) || !isSimpleString($breed)){
    die(json_encode(array(array('type' => 'error', 'content' =>  "Invalid request."))));
  }

  // Get filtered pets
  $pets = getFilteredPets($species, $gender, $sizes, $lowerAge, $upperAge, $location, $color, $breed);

  // Remove pets from the user and mark favourites
  $result = filterResult($pets);
  
  die(json_encode($result));  
  

  /* 
  * Check if the filters were sent
  */
  function areFiltersSet(){
    return (isset($_POST['species']) && isset($_POST['gender']) && isset($_POST['sizes']) && 
    isset($_POST['age']) && isset($_POST['location']) && isset($_POST['color']) && isset($_POST['breed']));
  }

  /*
  * Add isFavourite flag to pets and remove pets from the user
  */
  function filterResult($pets){
  
    // If user is not signed in, there are no favourites
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
      return $pets;
    }

    // If user is signed in, mark his favourites
    $result = array();
    foreach($pets as $pet){
      if($pet['username'] != $_SESSION['username']){
        $pet['isFavourite'] = isFavourite($pet['petId'], $_SESSION['username']);
        array_push($result,$pet);
      }
    }

    return $result;
  }
?>