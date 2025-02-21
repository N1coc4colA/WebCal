<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settings.php#suppr?rm-error-meth");
    exit;
  }

  try {
    $pdo = connectDB();
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

    if (isset($_GET['account'])) {
      // Remove all indirect data
      // Remove informations about rendez-vous taken by the user.
      $stmt = $pdo->prepare("DELETE FROM INFO_DT WHERE id IN (SELECT info_id FROM AR_DT WHERE src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);
      // Rendez-vous made by this user
      $stmt = $pdo->prepare("DELETE FROM AR_DT WHERE (src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);

      // Remove all direct data from the tables
      // User account data
      $stmt = $pdo->prepare("DELETE FROM PENDING_DT WHERE (src=?)");
      $stmt->execute([$_SESSION["id"]]);
      $stmt = $pdo->prepare("DELETE FROM USR_DT WHERE (id=?)");
      $stmt->execute([$_SESSION["id"]]);

      session_destroy();
      header("Location: index.php");
      exit;
    } else if (isset($_GET['data'])) {
      // Remove indirect data
      // Remove informations about rendez-vous taken by the user.
      $stmt = $pdo->prepare("DELETE FROM INFO_DT WHERE id IN (SELECT info_id FROM AR_DT WHERE src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);
      // Rendez-vous made by this user
      $stmt = $pdo->prepare("DELETE FROM AR_DT WHERE (src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);

      header("Location: settings.php#suppr");
      exit;
    }

    header("Location: settings.php#suppr");
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error.html");
  }
?>
