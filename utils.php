<?php
  function generateToken() {
    return mb_strimwidth(htmlspecialchars(trim((string) bin2hex(random_bytes(32)))), 0, 32, "");
  }

  function connectDB() {
    $dsn = 'mysql:host=localhost;dbname=webcal;charset=utf8';
    $username = 'webcal-user';
    $password_db = 'webcal-pw';

    $pdo = new PDO($dsn, $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
  }
?>