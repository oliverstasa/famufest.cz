<?php
session_start();

  if (isset($_GET['to'])) {

    $_SESSION['rok'] = $_GET['to'];

  }

?>
