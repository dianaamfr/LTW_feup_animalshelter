<?php
    include_once('../includes/init.php');
    include_once('../utils/validation.php');
    include_once('../database/db_messages.php');
    include_once('../database/db_pet.php');


    // Check if user has signed in
    if(!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(json_encode(array(array('type' => 'error', 'content' =>  "Invalid request."))));
    }

    // Check if conversationId and lastId were sent
    if (!isset($_POST['conversationId']) || !isset($_POST['lastId'])) {
        die(json_encode(array(array('type' => 'error', 'content' =>  "Invalid request."))));
    }  
    
    // Check if ids are valid positive integers
    $conversationId = validate_positive_int($_POST['conversationId'],"Conversation");
    $lastId = $_POST['lastId'];
    
    // Check for error messages
    if(!empty($_SESSION['messages'])){
        die(json_encode(getApiMessages()));
    }

    // Check if the conversation is from the user
    if(!isConversationIntervenient($conversationId, $_SESSION['username'])){
        die(json_encode(array(array('type' => 'error', 'content' =>  "Invalid request."))));
    }

    // If there is a new message to send
    if (isset($_POST['text'])) {

        // Set sender and receiver of the new message
        $sender = $_SESSION['username'];

        $participants = getConversationParticipants($conversationId);
        if($participants['sender'] === $sender){   
            $receiver = $participants['receiver'];
        }
        else{
            $receiver = $participants['sender'];
        }

        // Set and validate message
        $messageText = validate_complex_string($_POST['text'], "Message Text");
        if(!empty($_SESSION['messages'])){
            die(json_encode(getApiMessages()));
        }

        $date = date('Y-m-j H:i:s');

        // Insert Message
        insertMessage($conversationId, $sender, $receiver, $messageText, $date);
    }

    // Retrieve new messages
    $messages = getUserMessagesByConversationId($conversationId, $lastId);

    // In order to get the most recent messages we have to reverse twice
    $messages = array_reverse($messages);

    die(json_encode($messages));
?>
