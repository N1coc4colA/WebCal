<?php
  session_start();

  if (!isset($_SESSION["id"])  || is_null($_SESSION["id"])) {
    header("Location: connect.php");
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settings.php?pw-error-meth")
    exit;
  }

  $dsn = 'mysql:host=localhost;dbname=webcal;charset=utf8';
  $username = 'webcal-user';
  $password_db = 'webcal-pw';

  try {
    $pdo = new PDO($dsn, $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $password = (string) mb_strimwidth(htmlspecialchars($_POST['password']), 0, 30, "");
    $nPassword = (string) mb_strimwidth(htmlspecialchars($_POST['nPassword']), 0, 30, "");
    // We don't care about confirmation password. Should be validated user-side only.

    $stmt = $pdo->prepare("SELECT pwh FROM USR_DT WHERE id = ?");
    $stmt->execute([$_SESSION["id"]]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pwh = $row["pwh"];

    if (!password_verify($password, $pwh)) {
      header("Location: settings.php?pw-error");
      exit;
    }

    $hashedPassword = password_hash($nPassword, PASSWORD_BCRYPT);
  
    // Update information
    $stmt = $pdo->prepare("UPDATE USR_DT SET pwh=? WHERE (id=?)");
    $stmt->execute([$hashedPassword, $_SESSION["id"]]);
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error.html");
    exit;
  }
?>
