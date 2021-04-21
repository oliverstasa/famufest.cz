<?php
session_start();

include '../fce.php';
include '../sql_open.php';

if (isset($_GET['n'])) {

  if (isset($_SESSION['lord']) == true) {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en, time FROM mag WHERE link = "'.$_GET['n'].'"';
  } else {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en, time FROM mag WHERE publikovano = "1" AND link = "'.$_GET['n'].'"';
  }
  $mag = mysqli_query($conn, $sql);

  if (mysqli_num_rows($mag) > 0) {

    $new = mysqli_fetch_assoc($mag);

      if ($new['thumb']) {
        $thumb = '/data/up/'.$new['thumb'];
      } else {
        $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
      }

    echo '
    <div class="novinka">
      <h1>'.lang($new['nazev'], $new['nazev_en']).'</h1>
      <img class="fullthumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
      <div class="fullpopis">
        <!-- <p>'.str_replace("font-family:Arial;", "", nl2br(lang($new['obsah'], $new['obsah_en']))).'</p> -->
        ';

            $obsah = lang($new['obsah'], $new['obsah_en']);
            $data = json_decode($obsah, true);
            if ($data['blocks'] != '') {
              json2html($data['blocks'], 'news');
            } else {
              echo nl2br($obsah);
            }

        echo '
      </div>
    </div>';

    if ($new['thumb']) {
      echo '
      <style>
        body {background: transparent;}
        .fullscreen_thumb {background: url(\''.$thumb.'\') center center; background-size: cover;}
      </style>
      ';
    }

  } else {

    echo '<h1>'.lang('ČLÁNEK NEEXISTUJE', 'ARTICLE DOESN\'T EXIST').'</h1>';

  }


} else {

  $now = date('Y-m-d H:i:s', time());
  $yearHolder = $_SESSION['rok'];
  $sql = 'SELECT
          nazev, nazev_en, link, thumb, cas_od, time, rok
          FROM mag
          WHERE cas_od < "'.$now.'" AND rok = "'.$_SESSION['rok'].'" AND publikovano = 1 ORDER BY cas_od DESC';
  $all_mag = mysqli_query($conn, $sql);

  if (mysqli_num_rows($all_mag) > 0) {

      while ($mag = mysqli_fetch_assoc($all_mag)) {

          if ($mag['thumb']) {
            $thumb = '/data/up/s/'.$mag['thumb'];
          } else {
            $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
          }

          if ($yearHolder != $mag['rok']) {

            echo '<div class="link_list"><h1>'.$mag['rok'].'</h1></div><br>';
            $yearHolder = $mag['rok'];

          }

          echo '
          <div class="news" link="/mag/'.$mag['link'].'">
            <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
            <h1>'.lang($mag['nazev'], $mag['nazev_en']).'</h1>
            <div class="time" title="'.tmstmp($mag['cas_od']).'">'.cas($mag['cas_od']).'</div>
          </div>
          ';

      }

  } else {

    echo '<h1>'.lang('ŽÁDNÉ ČLÁNKY', 'NO ARTICLES').'</h1>';

  }

}

include '../sql_close.php';
include '../document/lz.php';

?>
