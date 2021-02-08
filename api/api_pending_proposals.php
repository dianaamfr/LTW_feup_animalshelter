<?php
    include_once('../includes/init.php');
    include_once('../utils/validation.php');
    include_once('../database/db_proposals.php');

    // Check if user has signed in
    if(!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die(json_encode(array('type' => 'session', 'content' => 'You are not signed in')));
    }

    // Check if lastId was sent
    if (!isset($_POST['lastProposal'])) {
        die(json_encode(array('type' => 'error', 'content' =>  "Invalid request.")));
    }  

    $lastId = $_POST['lastProposal'];
    
    // Retrieve number of new pending proposals
    $proposals= getLastPendingProposals($lastId, $_SESSION['username']);

    die(json_encode($proposals));
?>