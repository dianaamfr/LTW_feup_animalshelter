<?php
include_once('../database/db_connection.php');

/**
 * Get all available pets
 */
function getAllPets() {
  $state = 'available';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId, pet.name as name, genderId, gender.name as gender, sizeId, size.name as size, speciesId, 
                        species.name as species, colorId, color.name as color, username, breed, description, date, location, age,
                        imageId, alternative
                        FROM pet 
                        JOIN gender USING (genderId) 
                        JOIN size USING (sizeId)
                        JOIN species USING(speciesId)
                        JOIN color USING(colorId)
                        JOIN user USING(username)
                        JOIN image USING(petId)
                        WHERE state == ?
                        GROUP BY petId
                        HAVING min(imageId)
                        ORDER BY petId ASC");
  $stmt->execute(array($state));
  return $stmt->fetchAll();
}

/**
 * Get the first 3 pets of the database (oldest ids)
 */
function getFirstPets() {
  $state = 'available';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId, name, description, location, imageId, alternative
                        FROM pet JOIN image USING(petId)
                        WHERE state == ?
                        GROUP BY petId
                        HAVING min(imageId)
                        ORDER BY petId ASC
                        LIMIT 3");
  $stmt->execute(array($state));
  return $stmt->fetchAll();
}

/**
 * Get pets filtered by multiple fields
 */
function getFilteredPets($species, $gender, $sizes, $lowerAge, $upperAge, $location, $color, $breed){
  $state = 'available';

  $db = Database::instance()->db();

  // Build Complex Select Statement

  // SELECT/FROM clause
  $query = "SELECT petId, pet.name as name, genderId, gender.name as gender, sizeId, size.name as size, speciesId,
                        species.name as species, colorId, color.name as color, username, breed, description, date, location, age, 
                        imageId, alternative
                        FROM pet 
                        JOIN gender USING (genderId) 
                        JOIN size USING (sizeId)
                        JOIN species USING(speciesId)
                        JOIN color USING(colorId)
                        JOIN user USING(username)
                        JOIN image USING(petId)
                        WHERE state == ?";
  
  // Create WHERE clause by examining selected filters

  $params = array();
  array_push($params, $state);

  if(!empty($species)){
    $species_place_holders = implode(',', array_fill(0, count($species), '?'));
    $query .= " AND speciesId IN ($species_place_holders)";
    
    foreach($species as $s)
      array_push($params, $s);
  }

  if(!empty($sizes)){
    $size_place_holders = implode(',', array_fill(0, count($sizes), '?'));
    $query .= " AND sizeId IN ($size_place_holders)";

    foreach($sizes as $size)
      array_push($params, $size);
  }
    
  if($gender != '-1'){
    $query .= " AND genderId == ?";
    array_push($params, $gender);
  }

  if($lowerAge != '0' || $upperAge != ' '){
    $query.= " AND";
    
    if($lowerAge == '0'){
      $query .= " age < ?";
      array_push($params, $upperAge);
    }
    else if($upperAge == ' '){
      $query .= " age > ?";
      array_push($params, $lowerAge);
    }
    else{
      $query .= " age >= ? AND age <= ?";
      array_push($params, $lowerAge, $upperAge);
    }

  }

  if($location != ''){
    $query .= " AND location LIKE ?";
    array_push($params, $location);
  }

  if($breed != ''){
    $query .= " AND breed LIKE ?";
    array_push($params, $breed);
  }

  if($color != '-1'){
    $query .= " AND colorId == ?";
    array_push($params, $color);
  }

  $query .= " GROUP BY petId HAVING min(imageId) ORDER BY petId ASC ";

  $stmt = $db->prepare($query);

  $stmt->execute($params);
  return $stmt->fetchAll();
}

/**
 * Get available Pets from the user
 */
function getMyPets($username) {
  $state = 'available';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId, pet.name as name, genderId, gender.name as gender, sizeId, size.name as size, speciesId, 
                        species.name as species, colorId, color.name as color, username, breed, description, date, location, age
                        FROM pet 
                        JOIN gender USING (genderId) 
                        JOIN size USING (sizeId)
                        JOIN species USING(speciesId)
                        JOIN color USING(colorId)
                        JOIN user USING(username)
                        WHERE state == ? AND username == ?
                        ORDER BY petId ASC");
  $stmt->execute(array($state, $username));
  return $stmt->fetchAll();
}

/**
 * Get a pet by his id
 */
function getPetById($id) {
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId, pet.name as name, genderId, gender.name as gender, sizeId, size.name as size, speciesId, 
                        species.name as species, colorId, color.name as color, username, breed, description, date, location, age,
                        state 
                        FROM pet 
                        JOIN gender USING (genderId) 
                        JOIN size USING (sizeId)
                        JOIN species USING(speciesId)
                        JOIN color USING(colorId)
                        JOIN user USING(username)
                        WHERE petId == ?
                        ");
  $stmt->execute(array($id));
  return $stmt->fetch();
}

/**
 * Get all the images of a pet by the id
 */
function getPetImagesByPetId($id) {
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT imageId, alternative
                        FROM image
                        WHERE petId == ?
                        ORDER BY imageId ASC");
  $stmt->execute(array($id));
  return $stmt->fetchAll();
}

/**
 * Get the number of images associated with a pet
 */
function getNumberOfImages($id) {
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT count(*) AS count
                        FROM image
                        WHERE petId == ?");
  $stmt->execute(array($id));
  return $stmt->fetch();
}

/**
 * Insert a pet
 */
function insertPet($name, $speciesId, $breed, $sizeId, $genderId, $age, $colorId, $description, $location, $username, $date){    
  $db = Database::instance()->db();
  $stmt = $db->prepare(
    "INSERT INTO pet (petId, name, speciesId, breed, sizeId, genderId, age, colorId, description, location, username, date) 
    VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $result = $stmt->execute(array($name, $speciesId, $breed, $sizeId, $genderId, $age, $colorId, $description, $location, $username, $date));
  
  // Return id of the inserted pet
  return $db->lastInsertId();
}

/**
 * Update pet information
 */
function updatePet($petId, $name, $speciesId, $breed, $sizeId, $genderId, $age, $colorId, $description, $location, $date){
  $db = Database::instance()->db();
  $stmt = $db->prepare("UPDATE pet 
                        SET name = ?, speciesId = ?, breed = ?, sizeId = ?, genderId = ?, age = ?, colorId = ?, description = ?, location = ?, date = ?
                        WHERE petId == ?");
  $stmt->execute(array($name, $speciesId, $breed, $sizeId, $genderId, $age, $colorId, $description, $location, $date, $petId));
}

/**
 * Insert new pet image
 */
function insertPetImage($petId, $description){
  $db = Database::instance()->db();
  $stmt = $db->prepare("INSERT INTO image VALUES(NULL, ?, ?)");
  $stmt->execute(array($petId,$description));

  // Return id of inserted image
  return $db->lastInsertId();
}

/**
 * Check if a user is the owner of a pet
 */
function checkIsPetOwner($username, $petId) {
  $db = Database::instance()->db();
  $stmt = $db->prepare('SELECT username FROM pet WHERE username == ? AND petId == ?');
  $stmt->execute(array($username, $petId));
  return $stmt->fetch()?true:false; // return true if a line exists
}

/**
 * Get the owner of a pet
 */
function getPetOwner($petId) {
  $db = Database::instance()->db();
  $stmt = $db->prepare('SELECT username FROM pet WHERE petId == ?');
  $stmt->execute(array($petId));
  return $stmt->fetch();
}

/**
 * Get favourite pets of a user
 */
function getFavouritePetsByUsername($username) {
  $state = 'available';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId, pet.name as name, genderId, gender.name as gender, sizeId, size.name as size, speciesId, 
                        species.name as species, colorId, color.name as color, pet.username as username, breed, description, date, location, age,
                        imageId, alternative 
                        FROM pet 
                        JOIN gender USING (genderId) 
                        JOIN size USING (sizeId)
                        JOIN species USING(speciesId)
                        JOIN color USING(colorId)
                        JOIN image USING(petId)
                        JOIN favourite USING(petId)
                        WHERE state == ? AND favourite.username == ?
                        GROUP BY petId
                        HAVING min(imageId)
                        ORDER BY petId ASC");
  $stmt->execute(array($state, $username));
  return $stmt->fetchAll();
}

/**
 * Insert new favourite pet
 */
function insertFavourite($petId,$username){
  $db = Database::instance()->db();
  $stmt = $db->prepare("INSERT INTO favourite VALUES(?, ?)");
  $stmt->execute(array($petId, $username));
}

/**
 * Delete pet from favourites
 */
function deleteFavourite($petId,$username){
  $db = Database::instance()->db();
  $stmt = $db->prepare("DELETE FROM favourite WHERE petId == ? AND username==?");
  $stmt->execute(array($petId, $username));
}

/**
 * Check if a pet is favourite of the user
 */
function isFavourite($petId,$username){ 
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId FROM favourite WHERE petId == ? AND username == ?");
  $stmt->execute(array($petId, $username));
  return $stmt->fetch()?true:false;// return true if a line exists
}

/**
 * Check if a pet is available
 */
function isAvailable($petId){
  $state = 'available';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT petId FROM pet WHERE petId == ? AND state == ?");
  $stmt->execute(array($petId, $state));
  return $stmt->fetch()?true:false;// return true if a line exists
}

/**
 * Delete a pet
 */
function deletePet($id) {
  $db = Database::instance()->db();
  $stmt = $db->prepare("DELETE FROM pet WHERE petId==?");
  $stmt->execute(array($id));
} 

?>
