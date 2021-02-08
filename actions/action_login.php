<?php
var_dump(get_include_path());
include_once('../includes/init.php');
include_once('../database/db_user.php');

  if (($_SERVER['REQUEST_METHOD'] !== 'POST') || isset($_SESSION['username']) || ($_SESSION['username'] != '')) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
  }

  // Verify username and password are correct
  if (isLoginCorrect($_POST['username'], $_POST['password'])){
    // Set session user
    setCurrentUser($_POST['username']);
    
    // If the user clicked to login from a page of the website(lastPage) then redirect to that page
    if (isset($_SESSION['lastPage']) && ($_SESSION['lastPage'] != '')) {
      die(header('Location: ' . $_SESSION['lastPage']));
    }
    // If the last page is not known redirect to the homepage
    die(header('Location: ' . '../pages/home.php'));
  }
  
  // Error message
  $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Login failed!');
  
  // Redirect to the login page
  die(header('Location: ' . $_SERVER['HTTP_REFERER']));
?>