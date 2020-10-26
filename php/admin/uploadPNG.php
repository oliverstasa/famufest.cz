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

if (isset($_FILES['thumb_input'])) {

$ext = pathinfo($_FILES['thumb_input']["name"], PATHINFO_EXTENSION);

  if (strtolower($ext) == 'png') {

    $new_name = false;
    while (file_exists('../../data/partneri/'.$new_name.'.png') || !$new_name) {
      $new_name = rand_nazev();
    }

    // upload it
    if (move_uploaded_file($_FILES['thumb_input']['tmp_name'], '../../data/partneri/'.$new_name.'.png')) {

        include '../sql_open.php';

          $dotaz = 'UPDATE '.$_GET['table'].' SET '.$_GET['key'].' = "'.$new_name.'.png" WHERE id = "'.$_GET['id'].'"';
           if (mysqli_query($conn, $dotaz)) {

             echo $new_name.'.png';

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
