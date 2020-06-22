<?php
session_start();

function resize_image($file, $meno, $w, $h, $crop = false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagejpeg($dst, $meno);
}

function rand_nazev() {
    $rand = false;
    $ch = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
    for ($i = 0; $i < 8; $i++) {
        $in = rand(0, strlen($ch) - 1);
        $rand .= $ch[$in];
    }
    return 'ff'.$_SESSION['rok'].$rand;
}

$ext = strtolower(pathinfo($_FILES['thumb_input']['name'], PATHINFO_EXTENSION));

switch ($ext) {

  default:
    $result = 'invalid';
  break;

  case 'jpg': case 'jpeg':

    $new_name = false;
    while (file_exists('../../data/up/'.$new_name.'.jpg') || !$new_name) {
      $new_name = rand_nazev();
    }

    $img_s = resize_image($_FILES['thumb_input']['tmp_name'], '../../data/up/s/'.$new_name.'.jpg', 800, 800);
    $img = resize_image($_FILES['thumb_input']['tmp_name'], '../../data/up/'.$new_name.'.jpg', 2048, 2048);

    if ($img_s && $img) {

        include '../sql_open.php';

          $dotaz = 'UPDATE '.$_GET['table'].' SET thumb = "'.$new_name.'.jpg" WHERE id = "'.$_GET['id'].'"';
           if (mysqli_query($conn, $dotaz)) {
              $result = $new_name.'.jpg';
           } else {
             $result = 'error';
           }

        include '../sql_close.php';

    } else {
      $result = 'error';
    }

  break;
}

echo $result; //.'/'.$_GET['table'].'/'.$_GET['id']

?>
