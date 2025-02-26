<?php
  function generateToken() {
    return mb_strimwidth(htmlspecialchars(trim((string) bin2hex(random_bytes(32)))), 0, 32, "");
  }

  function buildToken($tokName) {
    $tok = generateToken();
    $_SESSION[$tokName] = $tok;
    return $tok;
  }

  function connectDB() {
    $dsn = 'mysql:host=localhost;dbname=webcal;charset=utf8';
    $username = 'webcal-user';
    $password_db = 'webcal-pw';

    $pdo = new PDO($dsn, $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
  }

  function san_pw($pw) {
    return mb_strimwidth(htmlspecialchars($pw), 0, 30, "");
  }

  function san_phone($phone) {
    return san_string($phone, 10);
  }

  function san_mail($mail) {
    return san_string($mail, 30);
  }

  function san_string($str, $len) {
    return (string) mb_strimwidth(htmlspecialchars(trim($str)), 0, $len, "");
  }

  function validate_mail($mail) {
    return filter_var($mail, FILTER_VALIDATE_EMAIL);
  }

  function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', (string) $phone) == 1;
  }

  function validate_pw($pw) {
    return preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}/', $pw) == 1;
  }

  function validate_tok($tokName) {
    return isset($_SESSION[$tokName]) && !hash_equals($_SESSION[$tokName], mb_strimwidth(htmlspecialchars(trim($_POST[$tokName])), 0, 32, ""));
  }
?>
