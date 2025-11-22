<?php
  include "session_utils.php";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: calendar.php?alert=error-meth");
    exit;
  }
  if (!validate_tok("slot-res-token")) {
    if (isset($_POST["date"])) {
        header("Location: calendar.php?alert=error-meth&date=" . urlencode($_POST["date"]));
    } else {
        header("Location: calendar.php?alert=error-meth");
    }
    exit;
  }

  $date = san_string($_POST['date'], 10);
  $time = san_string($_POST['time'], 8);
  $message = san_string($_POST['message'], 200);

  if (!validate_string($date) || !validate_string($time)) {
    header("Location: calendar.php?alert=error-meth&date=" . urlencode($date));
    exit;
  }

  try {
      $nextTime = new DateTime($time);

      // Add an hour
      $nextTime->modify('+1 hour');

      // Format the time back to a string if needed
      $nextTimeString = $nextTime->format('H:i:s');

      DBAtomic::run(function($pdo) use ($nextTimeString, $date, $time, $message) {
        $stmt = $pdo->prepare("INSERT INTO AR_DT(src, beg_date, beg_time, end_date, end_time, msg) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION["id"], $date, $time, $date, $nextTimeString, $message]);
      });
  } catch (Exception $e) {
      header("Location: calendar.php?alert=error-meth");
      exit;
  }

  header("Location: calendar.php?alert=success&date=" . urlencode($_POST["date"]));
?>
