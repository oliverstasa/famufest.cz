<?php
session_start();

include '../fce.php';
include '../sql_open.php';

if (isset($_GET['p'])) {
  
  $now = date('Y-m-d H:i:s', time());

  if (isset($_SESSION['lord']) == true) {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en FROM pages WHERE link = "'.$_GET['p'].'"';
  } else {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en FROM pages WHERE publikovano = "1" AND cas_od < "'.$now.'" AND cas_do > "'.$now.'" AND link = "'.$_GET['p'].'"';
  }

  $pgs = mysqli_query($conn, $sql);

  if (mysqli_num_rows($pgs) > 0) {

    $pg = mysqli_fetch_assoc($pgs);

      if ($pg['thumb']) {
        $thumb = '/data/up/'.$pg['thumb'];
      } else {
        $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
      }

    echo '
    <div class="novinka">
      <h1>'.lang($pg['nazev'], $pg['nazev_en']).'</h1>
      <img class="fullthumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
      <div class="fullpopis">
        ';

            $obsah = lang($pg['obsah'], $pg['obsah_en']);
            $data = json_decode($obsah, true);
            if ($data['blocks'] != '') {
              json2html($data['blocks'], 'news');
            } else {
              echo nl2br($obsah);
            }

        echo '
      </div>
    </div>';

    if ($pg['thumb']) {
      echo '
      <style>
        body {background: transparent;}
        .fullscreen_thumb {background: url(\''.$thumb.'\') center center; background-size: cover;}
      </style>
      ';
    }

  } else {

    echo '<h1>'.lang('STRÁNKA NEEXISTUJE', 'PAGE DOESN\'T EXIST').'</h1>';

  }


} else {

  $now = date('Y-m-d H:i:s', time());
  $yearHolder = $_SESSION['rok'];
  $sql = 'SELECT
          nazev, nazev_en, link, thumb
          FROM pages
          WHERE cas_od < "'.$now.'" AND publikovano = 1 ORDER BY cas_od DESC'; // WHERE rok = "'.$_SESSION['rok'].'" AND ...
  $all_news = mysqli_query($conn, $sql);

  if (mysqli_num_rows($all_news) > 0) {

      while ($pgs = mysqli_fetch_assoc($all_news)) {

          if ($pgs['thumb']) {
            $thumb = '/data/up/s/'.$pgs['thumb'];
          } else {
            $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
          }

          $rokr = substr($pgs['cas_od'], 0, 4);

          if ($yearHolder != $rokr) {

            echo '<div class="link_list"><h1>'.$rokr.'</h1></div><br>';
            $yearHolder = $rokr;

          }

          echo '
          <div class="news" link="/news/'.$pgs['link'].'">
            <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
            <h1>'.lang($pgs['nazev'], $pgs['nazev_en']).'</h1>
          </div>
          ';

      }

  } else {

    echo '<h1>'.lang('ŽÁDNÉ STRÁNKY', 'NO PAGES').'</h1>';

  }

}

include '../sql_close.php';
include '../document/lz.php';

?>
