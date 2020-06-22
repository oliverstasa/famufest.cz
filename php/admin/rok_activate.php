<?php

  include '../sql_open.php';

  $sql = 'UPDATE rok SET active = 0';
  mysqli_query($conn, $sql);

  $sql = 'UPDATE rok SET active = 1 WHERE id = '.$_GET['to'];
  mysqli_query($conn, $sql);

  include '../sql_close.php';

?>
