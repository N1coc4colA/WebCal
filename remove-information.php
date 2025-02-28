<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settings.php#suppr?alert=rm-error-meth");
    exit;
  }

  try {
    $pdo = connectDB();
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

    if (isset($_GET['account'])) {
      if (!validate_tok("rm-account-token")) {
        header("Location: settings.php#suppr?alert=rm-error-meth");
        exit;
      }

      $stmt = $pdo->prepare("SELECT mail FROM USR_DT WHERE id=?");
      $stmt->execute([$_SESSION["id"]]);
      $mail = stmt->fetch()["mail"];

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

      $subject = "Vérification de votre email";
      $message = "Bonjour $surname,\nCe mail vous est envoyé pour confirmer que votre compte a bien été supprimé.\nCordialement,\nL'équipe gestion des données.";
      sendMail($mail, $subject, $message, $message);

      session_destroy();
      header("Location: index.php");
      exit;
    } else if (isset($_GET['data'])) {
      if (!validate_tok("rm-data-token")) {
        header("Location: settings.php#suppr?alert=rm-error-meth");
        exit;
      }

      // Remove indirect data
      // Remove informations about rendez-vous taken by the user.
      $stmt = $pdo->prepare("DELETE FROM INFO_DT WHERE id IN (SELECT info_id FROM AR_DT WHERE src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);
      // Rendez-vous made by this user
      $stmt = $pdo->prepare("DELETE FROM AR_DT WHERE (src=? OR dst=?)");
      $stmt->execute([$_SESSION["id"], $_SESSION["id"]]);
    }
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error");
  }

  header("Location: settings.php#suppr?alert=success-rm");
?>
