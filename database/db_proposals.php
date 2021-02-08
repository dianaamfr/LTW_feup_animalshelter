<?php
include_once('../database/db_connection.php');

/**
 * Get proposals made by the user
 */
function getUserProposals($username){
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT proposalId, proposalText, proposal.date, proposal.state as proposalState, pet.state as petState, 
                        petId, name, pet.username as owner, imageId, alternative
                        FROM proposal JOIN pet USING(petId) JOIN image USING(petId)
                        WHERE proposal.username == ?
                        GROUP BY proposalId
                        HAVING min(imageId)
                        ORDER BY proposal.date DESC");
  $stmt->execute(array($username));
  return $stmt->fetchAll();
}

/**
 * Get pending proposals
 */
function getPendingProposals($username){
  $proposalState = 'waiting';
  $petState = 'available';
  
  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT proposalId, proposalText, proposal.date, petId, name, proposal.username, imageId, 
                        alternative, proposal.state as proposalState
                        FROM proposal JOIN pet USING(petId) JOIN image USING(petId)
                        WHERE pet.username == ? AND pet.state == ? AND proposal.state == ?
                        GROUP BY proposalId
                        HAVING min(imageId)
                        ORDER BY proposal.date DESC");
  $stmt->execute(array($username,$petState,$proposalState));
  return $stmt->fetchAll();
}

/**
 * Get new proposals received after the one with id=lastId
 */
function getLastPendingProposals($lastId, $username){
  $proposalState = 'waiting';

  $db = Database::instance()->db();
  $stmt = $db->prepare("SELECT proposalId
                        FROM proposal JOIN pet USING(petId)
                        WHERE proposal.state == ? AND proposalId > ? AND pet.username == ?"); 
  $stmt->execute(array($proposalState, $lastId, $username));
  return $stmt->fetchAll();
}

/**
 * Check if the proposal was made to a pet of the user
 */
function checkIsProposalOwner($username, $proposalId) {
  $db = Database::instance()->db();
  $stmt = $db->prepare('SELECT petId FROM proposal JOIN pet USING(petId) WHERE pet.username == ? AND proposalId == ?');
  $stmt->execute(array($username, $proposalId));
  return $stmt->fetch();
}

/**
 * Check if a proposal exists, so that the owner of a pet can only send a message to a user that made a proposal
 */
function existsProposal($petId, $username) {
  $db = Database::instance()->db();
  $stmt = $db->prepare('SELECT proposalId FROM proposal WHERE username == ? AND petId == ?');
  $stmt->execute(array($username, $petId));
  return $stmt->fetch() ? true : false;
}

/**
 * Check if the proposal was not yet accepted nor rejected (is waiting a response)
 */
function isProposalWaiting($petId, $username) {
  $state  = 'waiting';

  $db = Database::instance()->db();
  $stmt = $db->prepare('SELECT proposalId FROM proposal WHERE username == ? AND petId == ? AND state == ?');
  $stmt->execute(array($username, $petId, $state));
  return $stmt->fetch() ? true : false;
}

/**
 * Insert a new porposal
 */
function insertProposal($petId, $username, $proposalText, $date){
  $db = Database::instance()->db();
  $stmt = $db->prepare("INSERT INTO proposal (proposalId, petId, username, proposalText, date) VALUES (NULL, ?, ?, ?, ?)");
  $stmt->execute(array($petId, $username, $proposalText, $date));
}

/**
 * Update the state of the proposal(Accept or Reject proposal)
 */
function updateProposal($proposalId, $state){
  $db = Database::instance()->db();
  $stmt = $db->prepare("UPDATE proposal 
                        SET state = ?
                        WHERE proposalId = ?");
  $stmt->execute(array($state,$proposalId));
}

?>