<?php
session_start();

include '../fce.php';
include '../sql_open.php';

if (isset($_GET['n'])) {

  if (isset($_SESSION['lord']) == true) {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en, time FROM news WHERE link = "'.$_GET['n'].'"';
  } else {
    $sql = 'SELECT nazev, nazev_en, thumb, obsah, obsah_en, time FROM news WHERE publikovano = "1" AND link = "'.$_GET['n'].'"';
  }
  $news = mysqli_query($conn, $sql);

  if (mysqli_num_rows($news) > 0) {

    $new = mysqli_fetch_assoc($news);

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

    echo '<h1>'.lang('NOVINKA NEEXISTUJE', 'NEWS DOESN\'T EXIST').'</h1>';

  }


} else {

  $now = date('Y-m-d H:i:s', time());
  $yearHolder = $_SESSION['rok'];
  $sql = 'SELECT
          nazev, nazev_en, link, thumb, cas_od, time, rok
          FROM news
          WHERE cas_od < "'.$now.'" AND publikovano = 1 ORDER BY cas_od DESC'; // WHERE rok = "'.$_SESSION['rok'].'" AND ...
  $all_news = mysqli_query($conn, $sql);

  if (mysqli_num_rows($all_news) > 0) {

      while ($news = mysqli_fetch_assoc($all_news)) {

          if ($news['thumb']) {
            $thumb = '/data/up/s/'.$news['thumb'];
          } else {
            $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
          }

          if ($yearHolder != $news['rok']) {

            echo '<div class="link_list"><h1>'.$news['rok'].'</h1></div><br>';
            $yearHolder = $news['rok'];

          }

          echo '
          <div class="news" link="/news/'.$news['link'].'">
            <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
            <h1>'.lang($news['nazev'], $news['nazev_en']).'</h1>
            <div class="time" title="'.tmstmp($news['cas_od']).'">'.cas($news['cas_od']).'</div>
          </div>
          ';

      }

  } else {

    echo '<h1>'.lang('ŽÁDNÉ NOVINKY', 'NO NEWS').'</h1>';

  }

}

include '../sql_close.php';
include '../document/lz.php';

?>
