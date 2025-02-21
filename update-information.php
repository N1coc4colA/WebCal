<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settings.php?upd-error-meth");
    exit;
  }

  $name = (string) mb_strimwidth(htmlspecialchars(trim($_POST['lastname'])), 0, 30, "");
  $surname = (string) mb_strimwidth(htmlspecialchars(trim($_POST['firstname'])), 0, 30, "");
  $birthdate = htmlspecialchars(trim($_POST['birthdate']));
  $address = (string) mb_strimwidth(htmlspecialchars(trim($_POST['address'])), 0, 50, "");
  $phone = mb_strimwidth(htmlspecialchars(trim($_POST['phone'])), 0, 10, "");
  $email = (string) mb_strimwidth(filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL), 0, 30, "");

  if (!preg_match('/^[0-9]{10}$/', $phone)) {
    header("Location: settings.php?phone-error");
    exit;
  }

  try {
    $pdo = connectDB();

    $stmt = $pdo->prepare("SELECT id FROM USR_DT WHERE email = ?");
    $stmt->execute([$email]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $rowCount = count($ids);

    if ($rowCount > 1) { // Looks like we got an error from somewhere. Very unlikely.
      header("Location: settings.php?mail-error");
      exit;
    }
    if ($rowCount == 1 && $_SESSION["id"] != $ids[0]) { // Not the current account.
      header("Location: settings.php?mail-error");
      exit;
    }

    // Update information
    // Do not forget to invalidate user verification.
    $stmt = $pdo->prepare("UPDATE USR_DT SET name=?, surname=?, birthdate=?, address=?, phone=?, email=?, sub=? WHERE (id=?)");
    $stmt->execute([$name, $surname, $birthdate, $address, $phone, $email, 0, $_SESSION["id"]]);

    // [TODO] Send verification mail
    // Generate entry first
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $verificationCode = mb_strimwidth(bin2hex(random_bytes(20)), 0, 30, "");
    $verificationLink = "http://localhost/registration-success.php?code=$verificationCode";

    // Store verification code
    $stmt = $pdo->prepare("INSERT INTO PENDING_DT (sub_date, sub_time, validator, src) VALUES (?, ?, ?, ?)");
    $stmt->execute([$date, $time, $verificationCode, $_SESSION["id"]]);

    $subject = "Vérification de votre email";
    $message = "Bonjour $surname,\n\nCliquez sur le lien suivant pour vérifier votre email :\n$verificationLink\n\nCordialement,\nL'équipe d'inscription.";
    $headers = "From: noreply@localhost" . "\r\n";
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error.html");
    exit;
  }

  header("Location: settings.php");
?>
