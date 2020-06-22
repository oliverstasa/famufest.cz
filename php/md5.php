<?php
session_start();

  if ($_POST['txt']) {

    echo md5($_POST['txt'].'FAMUFEST'.$_SESSION['rok']);

  }

?>
