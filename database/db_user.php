<?php
include_once('../database/db_connection.php');

/**
 * Checks if the username and password correspond to a user
 */
function isLoginCorrect($username, $password) {
  $db = Database::instance()->db();
  
  $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
  $stmt->execute(array($username));

  $user = $stmt->fetch();
  return $user !== false && password_verify($password, $user['password']);
}

/**
 * Insert new user
 */
function insertUser($username, $password) {
  $db = Database::instance()->db();

  $options = ['cost' => 12];

  $stmt = $db->prepare('INSERT INTO user VALUES(?, ?)');
  $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT, $options)));
}

/**
 * Change user data
 */
function changeUser($newusername, $newpassword,$username) {
  $db = Database::instance()->db();

  $options = ['cost' => 12];
  
  $stmt = $db->prepare('UPDATE user SET username = ?, password = ? WHERE username == ?');
  $stmt->execute(array($newusername, password_hash($newpassword, PASSWORD_DEFAULT, $options), $username));
} 

/**
 * Delete user profile
 */
function deleteProfile($username) {
  $db = Database::instance()->db();
  $stmt = $db->prepare("DELETE FROM user WHERE username==?");
  $stmt->execute(array($username));
} 

?>