<?php
    include_once('../includes/init.php');
    include_once('../templates/proposals/tpl_proposals.php');
    include_once('../templates/common/tpl_common.php');
    include_once('../database/db_proposals.php');
    setLastPage($_SERVER['REQUEST_URI']);
    
    drawHeader();

    // If the user is already logged in
    if (isset($_SESSION['username']) && ($_SESSION['username'] != '')){
        $proposals = getPendingProposals($_SESSION['username']);
        drawReceivedProposals($proposals);
    }
    else{
        drawReceivedProposals(NULL);
    }

    drawFooter();
?>