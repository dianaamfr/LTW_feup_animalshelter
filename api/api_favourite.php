<?php
    include_once('../includes/session.php');
    include_once('../database/db_user.php');
    include_once('../database/db_pet.php');

    // Verify if user is logged in
    if (!isset($_SESSION['username'])){
        die(json_encode(array('type' => 'session', 'content' => 'You are not signed in')));
    } 
    
    // Get user if he is logged in
    $username = $_SESSION['username'];

    // Add or remove from favourites if a petId was specified
    if(isset($_POST['petId'])){
        $petId = json_decode($_POST['petId']);

        // Check if the user is the owner of the pet
        if(checkIsPetOwner($username, $petId)){
            die(json_encode(array('type' => 'error', 'content' => 'Invalid request')));
        }

        // If the pet is not a favourite, then add it to favourites
        if (!isFavourite($petId,$username) && isAvailable($petId)) {
            insertFavourite($petId,$username);
            die(json_encode(array('type' => 'add', 'petId' => $petId)));
        // Otherwise, remove the pet from favourites
        } else {
            deleteFavourite($petId,$username);
            die(json_encode(array('type' => 'remove', 'petId' => $petId)));
        }
    }
    
    // Return all favourites from the user if no petId was specified
    $favouritePets = getFavouritePetsByUsername($username);

    die(json_encode($favouritePets));

?>    