<?php

$dbPath = __DIR__ . '/dataBase.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY, email TEXT, password TEXT);');


