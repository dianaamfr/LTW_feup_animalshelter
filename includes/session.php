<?php
  session_start();

  if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = generate_random_token();
  }

  function generate_random_token() {
    return bin2hex(openssl_random_pseudo_bytes(32));
  }

  function setCurrentUser($username) {
    $_SESSION['username'] = $username;
  }

  function setLastPage($lastPage) {
    $_SESSION['lastPage'] = $lastPage;
  }

  function getMessages() {
    if (isset($_SESSION['messages'])){
      return $_SESSION['messages'];
    }
    else{
      return array();
    }
  }

  function clearMessages() {
    unset($_SESSION['messages']);
  }

?>