<?php
    include_once('../includes/session.php');
    include_once('../utils/validation.php');
    include_once('../database/db_proposals.php');
    include_once('../database/db_pet.php');

    // Verify if user is logged in
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(header('Location: ../pages/login.php'));
    }
    $username = $_SESSION['username'];

    // Validate Post variables
    if (!$_SERVER["REQUEST_METHOD"] == "POST"){
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    if(empty($_POST['petId'])){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }
    $petId = validate_positive_int($_POST['petId'], 'Pet Id');

    $proposalText = validate_complex_string($_POST['proposalText'], 'Proposal Text');
    
    if(!empty($_SESSION['messages'])){
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Check if that pet is from the user - a user can't propose to adopt his own pets
    // Check if the pet is available - don't allow proposals if the pet is not available
    if(checkIsPetOwner($username, $petId) || !isAvailable($petId)){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Check if a proposal was already made by the user to adopt this pet and is still waiting for a response
    if(isProposalWaiting($petId, $username)){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Be patient! Your previous proposal is waiting response.');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    $date = date('Y-m-j H:i:s');

    // Insert Proposal in database
    insertProposal($petId, $username, $proposalText, $date);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>    