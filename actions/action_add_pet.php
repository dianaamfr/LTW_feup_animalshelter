<?php
    include_once('../includes/session.php');
    include_once('../utils/validation.php');
    include_once('../database/db_user.php');
    include_once('../database/db_pet.php');

    // Verify if user is logged in
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(header('Location: ../pages/login.php'));
    }
    
    // Verifies CSRF token
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    $username = $_SESSION['username'];

    // Get & verify pet data from user input
    
    if ($_SERVER["REQUEST_METHOD"] != "POST")
        die(header('Location:' . $_SERVER['HTTP_REFERER']));

    if(!isset($_POST['name']) || !isset($_POST['breed']) || !isset($_POST['location']) || !isset($_POST['speciesId']) ||
    !isset($_POST['sizeId']) || !isset($_POST['genderId']) || !isset($_POST['age']) || !isset($_POST['colorId'])){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Validate Name
    $name = validate_string($_POST["name"],'Name');
    
    // Validate Description
    $description = validate_long_string($_POST['description'],'Description');
    
    // Validate breed
    if (empty($_POST["breed"])) 
        $breed = "";
    else
        $breed = validate_string($_POST["breed"],'Breed');

    // Validate Location
    $location = validate_string($_POST["location"],'Location');

    // Validate Species
    $speciesId = validate_positive_int($_POST["speciesId"],'Species');

    // Validate Size
    $sizeId = validate_positive_int($_POST["sizeId"],'Size');

    // Validate Gender 
    $genderId = validate_positive_int($_POST["genderId"],'Gender');

    // Validate Age
    $age = validate_positive_int($_POST["age"],'Age ');

    // Validate Color
    $colorId = validate_positive_int($_POST["colorId"],'Color');
    
    $date = date('Y-m-j');

    // Check image descriptions & uploads
    $imagesAlts = array();
    $imagesAltsTemp = $_POST['imagesAlts'];
    if (!is_array($imagesAltsTemp)){
        $_SESSION['messages'][] = array('type' => 'error', 'content' =>  "Invalid images array.");
    }
    else {
        for($i = 0 ; $i < count($imagesAltsTemp); $i++) {
            
            if($i == 0 && (empty($imagesAltsTemp[$i]) || empty($_FILES['petImages']['tmp_name'][$i]))){
                $_SESSION['messages'][] = array('type' => 'error', 'content' => "Main image title and file are required.");
                break;
            }
            else{
                // Image Title not specified but file uploaded
                if(empty($imagesAltsTemp[$i]) && !empty($_FILES['petImages']['tmp_name'][$i])){
                    $_SESSION['messages'][] = array('type' => 'error', 'content' => "Missing title for uploaded image.");
                    break;
                }

                // Image Title specified for null image
                if(!empty($imagesAltsTemp[$i]) && empty($_FILES['petImages']['tmp_name'][$i])){
                    $_SESSION['messages'][] = array('type' => 'error', 'content' => "Missing image for given title.");
                    break;
                }
            }

            if(empty($imagesAltsTemp[$i])) continue;

            $imageAlt = test_input($imagesAltsTemp[$i]);
            // check if name only contains non accented letters and whitespace
            if (!preg_match("/^[A-Za-z0-9 ]{3,}$/",$imageAlt)) {
                $_SESSION['messages'][] = array('type' => 'error', 'content' => "Invalid image title. Only non accented letters, numbers and white spaces allowed. Min 3 letters.");
                break;
            }
            array_push($imagesAlts, $imageAlt);
        }
    }
    
    if(!empty($_SESSION['messages']))
        die(header('Location:' . $_SERVER['HTTP_REFERER']));

    $files = array_filter($_FILES['petImages']['tmp_name']); // Delete empty file names
    $input_files_count = count($files); // Number of files - not including empty ones
    if($input_files_count <= 0){
        $_SESSION['messages'][] = array('type' => 'error', 'content' =>  "Invalid number of images.");
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Insert Pet in database and get pet Id  
    $petId = insertPet($name, $speciesId, $breed, $sizeId, $genderId, $age, $colorId, $description, $location, $username, $date);


    // Upload and Insert Images in the database
    $max_files = $input_files_count < 4 ? $input_files_count : 4;
    $uploaded = 0;
    $total = count($_FILES['petImages']['tmp_name']);
    
    for( $i = 0 ; $i < $total && $uploaded < $max_files; $i++ ) {
        
        $imgAlt = $imagesAlts[$i];
        $tmpFilePath = $_FILES['petImages']['tmp_name'][$i];

        if($tmpFilePath == '') continue; // Don't use empty files

        $uploaded++;

        // Insert pet image in the database
        $imgId = insertPetImage($petId, $imgAlt);

        $newFilePath = "../images/pets/originals/pet{$petId}img{$imgId}.jpg";
        $smallFilePath = "../images/pets/thumbs/pet{$petId}img{$imgId}.jpg";

        move_uploaded_file($tmpFilePath, $newFilePath);

        // Create an image representation of the original image
        $original = imagecreatefromjpeg($newFilePath);

        $width = imagesx($original);     // width of the original image
        $height = imagesy($original);    // height of the original image
        $square = min($width, $height);  // size length of the maximum square

        // Create and save a small square thumbnail
        $small = imagecreatetruecolor(200, 200);
        imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
        imagejpeg($small, $smallFilePath);

    }

    header('Location: ../pages/pets_i_found.php');

?>    