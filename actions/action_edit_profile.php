<?php
  include_once('../includes/session.php');
  include_once('../database/db_user.php');

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

  // Username and Password were not defined
  if((!isset($_POST['editUsername']) || ($_POST['editUsername'] == '')) && (!isset($_POST['editPassword']) || ($_POST['editPassword'] == ''))){
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Nothing to change!');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
  }
  
  //  Check if login is valid
  if (!isLoginCorrect($_SESSION['username'], $oldpassword)){
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Your password is incorrect.');
    die(header('Location:' . $_SERVER['HTTP_REFERER']));
  }
    

  // Valid Login

  // Username was defined
  if(isset($_POST['editUsername']) && ($_POST['editUsername'] != '')){
    $newusername = $_POST['editUsername'];

    // Verify username
    if (!preg_match("/^[_a-z0-9]{5,}$/", $newusername)) {
      $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid username');
      die(header('Location: ../pages/edit_profile.php'));
    }
  }
  // Otherwise use old username
  else{
    $newusername = $_SESSION['username'];
  }
  
  // Password was defined
  if(isset($_POST['editPassword']) && ($_POST['editPassword'] != '')){
     if(!isset($_POST['edit_password_repeat']) || ($_POST['edit_password_repeat'] == '')){
        $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Password repeat not defined!');
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
     }

      $newpassword = $_POST['editPassword'];
      $passwordrepeat = $_POST['edit_password_repeat'];

    // Verify password
    if(!preg_match("/^.*(?=.*[A-Z])(?=.*[!@#$&*%+=,\-\_\.;?])(?=.*[0-9]).{8,}$/", $newpassword)){
      $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid password');
      die(header('Location: ../pages/edit_profile.php'));
    }

    // Verify if password matches password_repeat
    if($newpassword !== $passwordrepeat){
      $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Passwords do not match');
      die(header('Location:'  . $_SERVER['HTTP_REFERER']));
    }

  }
  // Password was not defined
  else{
    // User old password if it was not set
    $newpassword = $oldpassword;
  }
  
  // Update user profile & set session
  try {
    changeUser($newusername, $newpassword, $_SESSION['username']);
    setCurrentUser($newusername);
  }
  catch (PDOException $e) {
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Failed to Edit!');
  }
  finally {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }

?>