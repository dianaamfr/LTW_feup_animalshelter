<?php
    include_once('../includes/session.php');
    include_once('../utils/validation.php');
    include_once('../database/db_pet.php');
    include_once('../database/db_messages.php');
    include_once('../database/db_proposals.php');

    // Verify if user is logged in
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(header('Location: ../pages/login.php'));
    }

    // Get message data
    $petId = validate_positive_int($_POST["petId"],'Pet');
    $sender = $_SESSION['username'];
    $messageText = validate_complex_string($_POST['messageText'],'Message Text');
    $date = date('Y-m-j H:i:s');

    $petOwner = getPetOwner($petId)['username'];
    // The owner is sending a message to an interested person
    if(isset($_POST["receiver"]) && ($petOwner === $sender) && (existsProposal($petId, $_POST["receiver"]))){
        $receiver = $_POST["receiver"];
    }
    // A person is sending a message to the owner of the pet
    else if($petOwner !== $sender){
        $receiver = getPetOwner($petId)['username'];
    }
    else {
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }

    if(!empty($_SESSION['messages'])){
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    //Get Conversation Id if it exists
    $conversation = existsConversation($petId, $sender, $receiver);
    
    if(empty($conversation)) {
        $conversation = insertConversation($petId, $sender);
    }

    // Insert Message in database
    insertMessage($conversation['conversationId'], $sender, $receiver, $messageText, $date);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>    