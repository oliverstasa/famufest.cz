<?php
session_start();

function rand_nazev() {
    $rand = false;
    $ch = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
    for ($i = 0; $i < 8; $i++) {
        $in = rand(0, strlen($ch) - 1);
        $rand .= $ch[$in];
    }
    return 'ff'.$_SESSION['rok'].$rand;
}

if (isset($_FILES['video_input'])) {

$ext = pathinfo($_FILES['video_input']["name"], PATHINFO_EXTENSION);

  if (strtolower($ext) == 'mp4') {

    $new_name = false;
    while (file_exists('../../data/up/'.$new_name.'.mp4') || !$new_name) {
      $new_name = rand_nazev();
    }

    // upload it
    if (move_uploaded_file($_FILES['video_input']['tmp_name'], '../../data/up/'.$new_name.'.mp4')) {

        include '../sql_open.php';

          $dotaz = 'UPDATE '.$_GET['table'].' SET '.$_GET['key'].' = "'.$new_name.'.mp4" WHERE id = "'.$_GET['id'].'"';
           if (mysqli_query($conn, $dotaz)) {

             echo $new_name.'.mp4';

           } else {

             echo 'error';

           }

        include '../sql_close.php';

    }

  } else {

    echo "invalid";

  }

} else {

  echo 'error';

}

?>
