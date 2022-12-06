<?php
  session_start(); // Star tb The SessionHandler

  session_unset(); // Unset The Data

  session_destroy(); // Destroy The Session

  header('location: index.php');

  exit();

 ?>
