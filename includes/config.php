<?php

$config = array();

$db = require_once '/../config/db.php';

$config = array_merge($config, $db);

try {
    global $db_connect;
    $db_connect = new PDO("mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'], $config['db']['login'], $config['db']['password']);
} catch (Exception $e) {
    echo "SQL connection error";
    die;
}
