<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

try {
    $db = DBManager::getInstance();
    $db->query("ALTER TABLE products ADD FULLTEXT (name, description)");
    echo "FULLTEXT index added successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
