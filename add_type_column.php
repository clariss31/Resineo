<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

try {
    $db = DBManager::getInstance();
    $sql = "ALTER TABLE messages ADD COLUMN type VARCHAR(20) DEFAULT 'text' AFTER sender_id";
    $db->query($sql);
    echo "Column 'type' added successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
