<?php

include './fce.php';

  //echo json_encode($_POST['data']['blocks']);
  $oblast = $_POST['oblast'];
  $data = $_POST['data']['blocks'];

  if ($oblast == 'news') {
    $goal = 'novinky';
  } else {
    $goal = 'page';
  }

  json2html($data, $goal);

?>
