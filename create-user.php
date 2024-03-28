<?php

$dbPath = __DIR__ . '/dataBase.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$email = 'email@example.com';
$password = '123';
$hash = password_hash($password, PASSWORD_ARGON2ID);

$sql = 'INSERT INTO users (email, password) VALUES (?, ?);';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $email);
$stmt->bindValue(2, $hash);
$stmt->execute();
