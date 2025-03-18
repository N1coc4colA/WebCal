<?php
  session_start();

  if (!isset($_SESSION["id"])  || is_null($_SESSION["id"])) {
    header("location: connect.php");
    exit;
  }

  include "utils.php";
?>
