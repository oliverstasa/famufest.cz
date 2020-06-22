<?php
session_start();

include '../sql_open.php';

$session = session_id();
$reload_time = $_POST['reload'];
$now = date('Y-m-d H:i:s', time());
$ago = date('Y-m-d H:i:s', strtotime('-'.$reload_time.' seconds'));
$res = mysqli_query($conn, 'SELECT COUNT(*) AS pocet FROM livecount WHERE id = "'.$session.'"');
if (mysqli_num_rows($res) > 0) {
  $result = mysqli_fetch_assoc($res);
  if ($result['pocet'] > 0) {
    mysqli_query($conn, 'UPDATE livecount SET time = "'.$now.'" WHERE id = "'.$session.'"');
  } else {
    mysqli_query($conn, 'INSERT INTO livecount(id, time) VALUES ("'.$session.'", "'.$now.'")');
  }
}
mysqli_query($conn, 'DELETE FROM livecount WHERE time < "'.$ago.'"');
$res = mysqli_query($conn, 'SELECT COUNT(*) AS pocet FROM livecount');
if (mysqli_num_rows($res) > 0) {
  $result = mysqli_fetch_assoc($res);
  echo $result['pocet'];
}

include '../sql_close.php';

?>
