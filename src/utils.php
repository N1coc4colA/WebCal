<?php
  include "db.php";

  function generateToken() {
    return mb_strimwidth(htmlspecialchars(trim((string) bin2hex(random_bytes(32)))), 0, 32, "");
  }

  function buildToken($tokName) {
    $tok = generateToken();
    $_SESSION[$tokName] = $tok;
    return $tok;
  }

  function connectDB() {
    return DB::getInstance();
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

  function validate_date($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
  }

  function validate_time($time) {
    return preg_match('/^\d{2}:\d{2}:\d{2}$/', $time);
  }

  function validate_string($value) {
    return isset($value) && $value !== "";
  }

  function validate_tok($tokName) {
    return isset($_SESSION[$tokName]) && isset($_POST["token"]) && hash_equals($_SESSION[$tokName], mb_strimwidth(htmlspecialchars(trim($_POST["token"])), 0, 32, ""));
  }

  function isWeekend($date) {
    $dayOfWeek = date('w', strtotime($date));
    return $dayOfWeek == 0 || $dayOfWeek == 6;
  }

  function setupDebug() {
    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
      echo $errstr . " - " . 0 . " - " . $errno . " - " . $errfile . " - " . $errline;
    }
    set_error_handler("exception_error_handler");
  }

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  function sendMail($target, $subject, $message, $plain) {
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->isSMTP();                                    //Send using SMTP
      $mail->Host       = getenv("SMTP_HOST");            //Set the SMTP server to send through
      $mail->SMTPAuth   = (bool)getenv("SMTP_AUTH");      //Enable SMTP authentication
      $mail->Username   = getenv("SMTP_USERNAME");        //SMTP username
      $mail->Password   = getenv("SMTP_PASSWORD");        //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
      $mail->Port       = getenv("SMTP_PORT");            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      $mail->SMTPOptions = array(
          "ssl" => array(
              "verify_peer" => false,
              "verify_peer_name" => false,
              "allow_self_signed" => true
          )
      );

      //Recipients
      $mail->setFrom(getenv("SMTP_MAIL_FROM"), "WebCal");
      $mail->addAddress($target);

      //Content
      $mail->isHTML(true);         //Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->AltBody = $plain;

      return $mail->send();
    } catch (Exception $e) {
      return false;
    }

    return true;
  }
?>
