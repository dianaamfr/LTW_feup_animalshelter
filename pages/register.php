<?php
  include_once('../includes/init.php');
  include_once('../templates/auth/tpl_auth.php');

  // If the user is already logged in
  if (isset($_SESSION['username']) && ($_SESSION['username'] != '')) {
    die(header('Location: ' . '../pages/home.php'));
  }

  drawHeader();
  drawRegister();
  drawFooter();
?>
