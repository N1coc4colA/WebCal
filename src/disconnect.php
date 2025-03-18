<?php
  include "session_utils.php";

  session_destroy();
  header("Location: index.php");
?>
