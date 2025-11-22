<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validate_tok("pw-ch-token")) {
    header("Location: settings.php#pw?alert=pw-error-meth");
    exit;
  }

  try {
    $password = san_pw($_POST['password']);
    $nPassword = san_pw($_POST['nPassword']);
    $id = $_SESSION["id"];

    // We don't care about confirmation password. Should be validated user-side only.
    $stmt = DB::getInstance()->prepare("SELECT pwh FROM USR_DT WHERE id=?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pwh = $row["pwh"];

    if (!password_verify($password, $pwh)) {
      header("Location: settings.php#pw?alert=pw-error");
      exit;
    }

    $hashedPassword = hash_password($nPassword);

    // Update information
    DBAtomic::run(function($pdo) use ($hashedPassword, $id) {
      $stmt = $pdo->prepare("UPDATE USR_DT SET pwh=? WHERE (id=?)");
      $stmt->execute([$hashedPassword, $_SESSION["id"]]);
    });
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error");
    exit;
  }

  header("Location: settings.php#pw?alert=success-pw-ch");
?>
