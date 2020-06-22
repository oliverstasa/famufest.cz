<?php
session_start();
unset($_SESSION['lord']);
if (!isset($_SESSION['lord'])) {
  echo 'loggedout';
} else {
  echo 'error';
}
?>
