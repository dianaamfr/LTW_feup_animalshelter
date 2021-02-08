
<?php
include_once('../database/db_connection.php');

/**
 * Get the last messages of each converesation of the user
 */
function getConversationsLastMessagesByUsername($username){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT conversationId, petId, messageId, messageText, sender, receiver, message.date as date, name,
                        imageId, alternative  
                        FROM conversation JOIN message USING (conversationId) JOIN pet USING(petId) JOIN image USING(petId)
                        WHERE receiver == ? OR sender == ?
                        GROUP BY conversationId
                        HAVING max(messageId)
                        ORDER BY message.date DESC, imageId ASC
                        ");
                      
  $stmt->execute(array($username,$username));
  return $stmt->fetchAll();
}

/**
 * Get all messages from a conversation that were sent after the one with id=lastId
 */
function getUserMessagesByConversationId($conversationId, $lastId){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT *
                        FROM conversation JOIN message USING(conversationId)
                        WHERE conversationId = ? AND messageId > ? 
                        ORDER BY date DESC, messageId DESC"); 
  $stmt->execute(array($conversationId, $lastId));
  return $stmt->fetchAll();
}

/**
 * Check if conversation exists - if notOwner sent any message about the pet with id=petId
 */
function existsConversation($petId, $sender, $receiver){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT conversationId
                        FROM conversation JOIN pet USING(petId) 
                        WHERE petId == ? AND ((notOwner == ? AND pet.username == ?) OR (notOwner == ? AND pet.username == ?))");
  $stmt->execute(array($petId, $sender, $receiver, $receiver, $sender));
  return $stmt->fetch();
}


/**
 * Check if a user is an intervenient of a conversation
 */
function isConversationIntervenient($conversationId, $username){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT conversationId
                        FROM conversation JOIN message USING(conversationId) 
                        WHERE conversationId == ? AND (sender == ? OR receiver == ?)");
  $stmt->execute(array($conversationId, $username, $username));
  return $stmt->fetch() ? true : false;
}

/**
 * Get the pet that a conversation is about
 */
function getPetByConversationId($conversationId){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT * 
                        FROM conversation JOIN pet USING(petId)
                        WHERE conversationId == ?
                        ");
  $stmt->execute(array($conversationId));
  return $stmt->fetch();
}

/**
 * Insert new conversation
 */
function insertConversation($petId, $notOwner){
  $db = Database::instance()->db();
  $stmt = $db->prepare("INSERT INTO conversation VALUES(NULL, ?, ?)");
  $stmt->execute(array($petId, $notOwner));

  // Return id of the inserted conversation
  return $db->lastInsertId();
}

/**
 * Insert message in a conversation
 */
function insertMessage($conversationId, $sender, $receiver, $messageText, $date){
  $db = Database::instance()->db();
  $stmt = $db->prepare("INSERT INTO message VALUES(NULL, ?, ?, ?, ?, ?)");
  $stmt->execute(array($conversationId, $sender, $receiver, $messageText, $date));
}

/**
 * Get the participants of a conversation
 */
function getConversationParticipants($conversationId){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT sender, receiver 
                        FROM conversation JOIN message USING (conversationId)
                        WHERE conversationId == ? AND messageId = (
                          SELECT max(messageId) as messageId
                          FROM conversation JOIN message USING (conversationId) WHERE conversationId == ?)
                        ");
  $stmt->execute(array($conversationId, $conversationId));
  return $stmt->fetch();
}

?>
