<?php
    include_once('../includes/init.php');
    include_once('../templates/common/tpl_common.php');
    include_once('../utils/validation.php');
    include_once('../templates/messages/tpl_messages.php');
    include_once('../database/db_messages.php');
    include_once('../database/db_pet.php');

    // If the user is not authenticated
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die( header('Location: ' . '../pages/login.php'));
    } 

    drawHeader();

    $username = $_SESSION['username'];
    if (!isset($_GET['id'])){
        die( header('Location: ' . '../pages/messages.php'));
    }
    $conversationId = validate_positive_int($_GET['id'],'Conversation');

    if(!empty($_SESSION['messages']))
        die( header('Location: ../pages/messages.php'));

    if(!isConversationIntervenient($conversationId, $username)){
        $_SESSION['messages'][] = array('type' => 'error', 'content' =>  "Invalid request.");
        die( header('Location: ../pages/messages.php'));
    }

    $pet = getPetByConversationId($conversationId);
    $petFirstImage = getPetImagesByPetId($pet['petId'])[0];
    drawConversation($conversationId, $pet, $petFirstImage);

    drawFooter();

?>