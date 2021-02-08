<?php
  include_once('../includes/init.php');
  include_once('../templates/auth/tpl_auth.php');

  // If the user is already authenticated
  if (isset($_SESSION['username']) && $_SESSION['username'] != '') {

    drawHeader();
    drawEditProfile();
    drawFooter();
  }
  else{
    header('Location: ' . '../pages/home.php');
  }
?>