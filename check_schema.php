<?php
require_once 'config/config.php';
require_once 'config/autoload.php';

try {
    $db = DBManager::getInstance();
    $stmt = $db->query("SHOW CREATE TABLE products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "CREATE TABLE statement:\n";
    echo $result['Create Table'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
