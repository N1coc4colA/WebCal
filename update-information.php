<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !validate_tok("info-upd-token")) {
    header("Location: settings.php#edit?upd-error-meth");
    exit;
  }

  $name = san_string($_POST['lastname'], 30);
  $surname = san_string($_POST['firstname'], 30);
  $birthdate = san_string($_POST['birthdate'], 20);
  $address = san_string($_POST['address'], 50);
  $phone = san_phone($_POST['phone']);
  $email = san_mail($_POST['email']);

  if (!validate_phone($phone)) {
    header("Location: settings.php#edit?phone-error");
    exit;
  }

  if (!validate_mail($email)) {
    header("Location: settings.php#edit?mail-error");
    exit;
  }

  try {
    $pdo = connectDB();

    $stmt = $pdo->prepare("SELECT id FROM USR_DT WHERE email=?");
    $stmt->execute([$email]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $rowCount = count($ids);

    if ($rowCount > 1) { // Looks like we got an error from somewhere. Very unlikely.
      header("Location: settings.php#edit?mail-error");
      exit;
    }
    if ($rowCount == 1 && $_SESSION["id"] != $ids[0]) { // Not the current account.
      header("Location: settings.php#edit?mail-error");
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
    header("Location: error.php?error=sql-error");
    exit;
  }

  header("Location: settings.php#edit?success-upd");
?>
