<?php
    include_once('../includes/init.php');
    include_once('../templates/common/tpl_common.php');
    include_once('../templates/proposals/tpl_proposals.php');
    include_once('../database/db_proposals.php');

    setLastPage($_SERVER['REQUEST_URI']);

    drawHeader();

    // Draw User proposals if he has signed in
    if (isset($_SESSION['username']) && ($_SESSION['username'] != '')){
        $proposals = getUserProposals($_SESSION['username']);
        drawMyProposals($proposals);
    }
    else{
        drawMyProposals(NULL);
    }

    drawFooter();
?>