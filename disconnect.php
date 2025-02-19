<?php
  session_start();

  if (!isset($_SESSION["id"])  || is_null($_SESSION["id"])) {
    header("Location: connect.php");
    exit;
  }

  session_destroy();
  header("Location: index.php");
?>
