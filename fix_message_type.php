<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

try {
    $db = DBManager::getInstance();
    $sql = "UPDATE messages SET type = 'quote_request' WHERE content LIKE '%\"items\":[%' AND type = 'text'";
    $db->query($sql);
    echo "Fixed messages.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
