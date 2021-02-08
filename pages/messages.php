<?php
    include_once('../includes/init.php');
    include_once('../templates/common/tpl_common.php');
    include_once('../templates/messages/tpl_messages.php');
    include_once('../database/db_messages.php');

    // If the user is not authenticated
    if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
        die( header('Location: ' . '../pages/login.php'));
    }

    drawHeader();

    $lastMessages = getConversationsLastMessagesByUsername($_SESSION['username']);
    drawConversationsLastMessages($lastMessages);

    drawFooter();

?>