<?php
session_start();
if ($_POST['name'] && $_POST['addr'] && $_POST['cas']) {
  include './sql_open.php';
    $sql = 'INSERT INTO score(name, addr, cas, pal, hits, rok) VALUES ("'.$_POST['name'].'", "'.$_POST['addr'].'", "'.$_POST['cas'].'", "'.$_POST['strel'].'", "'.$_POST['tref'].'", "'.$_SESSION['rok'].'")';
    if (mysqli_query($conn, $sql)) {
      echo 'done';
    } else {
      echo 'error|'.mysqli_error($conn);
    }
  include './sql_close.php';
} else {
  echo 'error';
}
?>
