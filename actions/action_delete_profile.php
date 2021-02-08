<?php
  include_once('../includes/session.php');
  include_once('../database/db_user.php');
  include_once('../includes/init.php');

  // Verify if user is logged in
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    die(header('Location: ../pages/login.php'));
  }

  // Verifies CSRF token
  if ($_SESSION['csrf'] != $_POST['csrf']) {
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid request!');
    die(header('Location:' . $_SERVER['HTTP_REFERER']));
  }

  if(!isset($_POST['oldPassword']) || ($_POST['oldPassword'] == '')){
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Please enter your old password.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
  }
  $oldpassword = $_POST['oldPassword'];

  // Delete profile and user pets/proposal etc if the password is valid
  if (isLoginCorrect($_SESSION['username'], $oldpassword)){
    deleteProfile($_SESSION['username']);
    session_destroy();
    session_start();
    die(header('Location: ../pages/login.php'));
  }
  // Error if password is incorrect
  else{
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Your password is incorrect.');
    die(header('Location:' . $_SERVER['HTTP_REFERER']));
  }
?>