<?php

  /*
  $server = 'sql.kytovci.cz';
  $user = 'u17778';
  $pass = 'IqhH2bOSeP';
  $db = "famufest_kytovci_cz";
  */

  $server = "127.0.0.1";
  $user = "famufest2020";
  $pass = "lvwJzMh9wr1J7513urJ0Pv06IH8XsBUF";
  $db = "famufest_2020";

  $conn = mysqli_connect($server, $user, $pass, $db);
  mysql_query("SET NAMES utf8mb4");
  mysqli_set_charset($conn, "utf8mb4");

  if (!$conn) {
    echo '<h1>DATABASE ERROR</h1>'.mysqli_connect_error();
    exit;
  }

?>
