<?php

/**
 * Get available pet sizes
 */
function getAllSizes() {
    $db = Database::instance()->db();
    $stmt = $db->prepare("SELECT * FROM size");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get available pet genders
 */
function getAllGenders() {
    $db = Database::instance()->db();
    $stmt = $db->prepare("SELECT * FROM gender");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get available pet colors
 */
function getAllColors() {
    $db = Database::instance()->db();
    $stmt = $db->prepare("SELECT * FROM color");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Get available pet species
 */
function getAllSpecies() {
    $db = Database::instance()->db();
    $stmt = $db->prepare("SELECT * FROM species");
    $stmt->execute();
    return $stmt->fetchAll();
}

?>
