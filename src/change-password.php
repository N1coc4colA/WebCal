<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validate_tok("pw-ch-token")) {
    header("Location: settings.php#pw?alert=pw-error-meth");
    exit;
  }

  try {
    $pdo = connectDB();

    $password = san_pw($_POST['password']);
    $nPassword = san_pw($_POST['nPassword']);
    // We don't care about confirmation password. Should be validated user-side only.

    $stmt = $pdo->prepare("SELECT pwh FROM USR_DT WHERE id=?");
    $stmt->execute([$_SESSION["id"]]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pwh = $row["pwh"];

    if (!password_verify($password, $pwh)) {
      header("Location: settings.php#pw?alert=pw-error");
      exit;
    }

    $hashedPassword = hash_password($nPassword);

    // Update information
    $stmt = $pdo->prepare("UPDATE USR_DT SET pwh=? WHERE (id=?)");
    $stmt->execute([$hashedPassword, $_SESSION["id"]]);
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error");
    exit;
  }

  header("Location: settings.php#pw?alert=success-pw-ch");
?>
