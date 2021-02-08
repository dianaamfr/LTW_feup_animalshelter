<?php
    include_once('../includes/session.php');
    include_once('../utils/validation.php');
    include_once('../database/db_user.php');
    include_once('../database/db_pet.php');

    // Verify if user is logged in and get its username
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(header('Location: ../pages/login.php'));
    }

    // Verifies CSRF token
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    $username = $_SESSION['username'];

    if(!isset($_POST['petId'])){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    $petId = validate_positive_int($_POST["petId"],'Id');

    // Check if that pet is from the user
    if(!checkIsPetOwner($username, $petId)){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Delete Pet, which trigger all related messages and proposals
    deletePet($petId);

    header('Location: ../pages/pets_i_found.php');

?>