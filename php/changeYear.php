<?php
session_start();

  if (isset($_POST['rok']) && is_numeric($_POST['rok'])) {

    include './fce.php';
    include './sql_open.php';

        $sql = 'SELECT COUNT(*) AS pocet FROM rok WHERE publikovano = 1 AND rok = "'.$_POST['rok'].'"';
        $roky = mysqli_query($conn, $sql);
        $rok = mysqli_fetch_assoc($roky);

    if ($rok['pocet'] == 1) {
      $_SESSION['rok'] = $_POST['rok'];
      echo 'ok';
    } else {
      echo 'ne';
    }

  } else {
    echo 'ne';
  }

?>
