<?php
session_start();

  switch($_SESSION['lang']) {
    case 'en': $lang = 'cs'; break;
    case 'cs': $lang = 'en'; break;
  }

  $_SESSION['lang'] = $lang;

?>
