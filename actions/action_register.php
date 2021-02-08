<?php
  include_once('../includes/session.php');
  include_once('../database/db_user.php');

  if (($_SERVER['REQUEST_METHOD'] !== 'POST') || isset($_SESSION['username']) || ($_SESSION['username'] != '')) {
    die(header('Location: ../pages/register.php'));
  }

  if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password_repeat'])){
    die(header('Location: ../pages/register.php'));
  }

  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_repeat = $_POST['password_repeat'];

  // Verify username - only lowercase letters, underscore or number, minimum 5 characters
  if (!preg_match("/^[_a-z0-9]{5,}$/", $username)) {
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid username');
    die(header('Location: ../pages/register.php'));
  }

  // Verify password - one capital letter, one lowercase letter, one symbol and one number, minimum of 8 letters
  if(!preg_match("/^.*(?=.*[A-Z])(?=.*[!@#$&*%+=,\-\_\.;?])(?=.*[0-9]).{8,}$/", $password)){
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Invalid password');
    die(header('Location: ../pages/register.php'));
  }

  // Verify if password matches password_repeat
  if($password !== $password_repeat){
    $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Passwords do not match');
    die(header('Location: ../pages/register.php'));
  }

  // Insert new user, start new session and redirect user to lastPage or home page
  try {
    insertUser($username, $password);
    setCurrentUser($username);

    // If the user was visiting other page of the website(lastPage) before registering, redirect him to that page
    if (isset($_SESSION['lastPage']) && ($_SESSION['lastPage'] != '')) {
      die(header('Location: ' . $_SESSION['lastPage']));
    }
    // Otherwise redirect him to the home page
    else {
      die(header('Location: ' . '../pages/home.php'));
    }
  }
  catch (PDOException $e) {
    if ($e->getCode() == '23000') 
      $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Username already exists!');
    else
      $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Failed to register!');

    // Redirect the user back to his previous location
    die(header('Location: ../pages/register.php'));
  }
  
?>