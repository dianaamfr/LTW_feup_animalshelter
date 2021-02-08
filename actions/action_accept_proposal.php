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

    // Verifies CSRF token
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    }

    // Validate Post variables
    if (!$_SERVER["REQUEST_METHOD"] == "POST")
        die(header('Location:' . $_SERVER['HTTP_REFERER']));

    if(empty($_POST['proposalId']))
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
    $proposalId = validate_positive_int($_POST['proposalId'], 'Proposal Id');

    if(!empty($_SESSION['messages']))
        die(header('Location:' . $_SERVER['HTTP_REFERER']));

    // Check if that proposal is from the user and get petId associated with it
    $petData = checkIsProposalOwner($username, $proposalId);
    // Unrelated ProposalId & Username
    if(empty($petData))
        die(header('Location:' . $_SERVER['HTTP_REFERER']));
   
    // Update proposal state in database => this also activates a trigger in the database
    // that updates every other proposal related to the same pet & changes the pet state to adopted
    $state = 'accepted';
    updateProposal($proposalId, $state);

    die(header('Location:' . $_SERVER['HTTP_REFERER']));
?>    