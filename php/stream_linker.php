<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['odkaz'])) {
  $odkaz_na_video = $_POST['odkaz'];
}


////////////////////////////////////////////////////////////////////////////////
// POKUD je IDLE (= není odkaz), tak vrátí odpočet času a nebo vygeneruje link pro další výpočet
if (isset($_GET['idle'])) {

  include './sql_open.php';

    $now = date('Y-m-d H:i:00', time());

    if ($_GET['idle'] == 'countdown') {

        $sql = 'SELECT cas_spusteni
                FROM kino
                WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'"';
        $dotaz = mysqli_query($conn, $sql);
        if (mysqli_num_rows($dotaz) > 0) {
          $data = mysqli_fetch_array($dotaz);

          $data['cas_spusteni'];
          $now = strtotime(date('Y-m-d H:i:s', time()));
          $spusti = strtotime($data['cas_spusteni']);
          if ($spusti > $now) {

            echo $zajakdlouho = $spusti - $now;

          }

        }

    } else if ($_GET['idle'] == 'getlink') {

      $sql = 'SELECT stream_link
              FROM kino
              WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'"';
      $dotaz = mysqli_query($conn, $sql);
      if (mysqli_num_rows($dotaz) > 0) {
        $data = mysqli_fetch_array($dotaz);

          if ($data['stream_link'] != "") {
            $odkaz_na_video = $data['stream_link'];
          } else {
            include './fce.php';
            echo '<div id="streamidle">'.lang('CHYBA STREAMU', 'STREAM ERROR').' <a href="live">'.lang('ZKUSIT ZNOVA', 'TRY AGAIN').' ↺</a></div>';
          }

      }

    }

  include './sql_close.php';

}

////////////////////////////////////////////////////////////////////////////////
// pokud je odkaz na video a nebo je zavolán testerem z administrace, vrací embed

if (isset($_POST['txt']) || isset($odkaz_na_video)) {

  if (isset($_POST['txt'])) {
    $link = $_POST['txt'];
    include_once './fce.php';
    $link = str_replace('"', '', $link);
    $link = str_replace(array("/carka/", "/uvozovka/", "/uvozovka2/", "/dvojtecka/", "/strednik/"), array(",", '\"', "'", ":", ";"), $link);
    $addon = true;
  } else {
    $link = $odkaz_na_video;
    $addon = false;
  }

  if (strpos($link, 'youtube') !== false || strpos($link, 'youtu.be') !== false) {

    if (strripos($link, '?v=') !== false) {
      $videolink = substr($link, strripos($link, '?v=')+3);
    } else if (strripos($link, '.be/') !== false) {
      $videolink = substr($link, strripos($link, '.be/')+4);
    }

    if (isset($videolink)) {
    if ($addon) {echo 'youtube|';}
    echo '
      <iframe type="text/html" src="https://www.youtube.com/embed/'.$videolink.'?autoplay=1&playsinline=1" frameborder="0" allowfullscreen></iframe>
    ';
    } else {
      echo 'error';
    }

  } else if (strpos($link, 'facebook') !== false) {

    if (strripos($link, 'videos/') !== false) {
      $videolink = substr($link, strripos($link, 'videos/')+7);
    } else if (strripos($link, '/?v=') !== false) {
      $videolink = substr($link, strripos($link, '/?v=')+4);
    }

    if (isset($videolink)) {

    $fbcode = '
      <div id="fb-root"></div>
      <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=228973553784785&autoLogAppEvents=1"></script>
      <div class="fb-video" data-href="'.$link.'" data-show-text="false" data-autoplay="true" style="position: absolute; top: 0; left: 0;"></div>
    ';
    $_SESSION['stream_fb'] = $fbcode;

    if ($addon) {
      echo 'facebook|<iframe style="outline: none; border: none;" id="profb" src="../../../php/stream_linker.php"></iframe>';
    } else {
      echo '<iframe style="outline: none; border: none;" id="profb" src="../php/stream_linker.php"></iframe>';
    }


  } else {
    echo 'error';
  }

  } else if (strpos($link, 'vimeo') !== false) {

    if (strpos($link, '/') !== false) {
      $videolink = substr($link, strripos($link, '/')+1);
    }

    if (isset($videolink)) {
    if ($addon) {echo 'vimeo|';}
    echo '
      <script type="text/javascript" src="https://player.vimeo.com/api/player.js"></script>
      <iframe src="https://player.vimeo.com/video/'.$videolink.'?loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    ';
    } else {
      echo 'error';
    }

  } else {

    if (isset($_POST['txt'])) {
      echo lang('CHYBA — NEFUNKČNÍ LIVESTREAM', 'ERROR — LIVESTREAM ERROR');
    } else {
      echo '
      <script src="/js/stream_loader.js" type="text/javascript"></script>
      <div id="streamidle"></div>
      ';
    }

  }

} else if (isset($_SESSION['stream_fb'])) {

  include './fce.php';
  echo $_SESSION['stream_fb'];
  unset($_SESSION['stream_fb']); // <style>@font-face {font-family: "regular"; src: url("/data/HelveticaNowText.woff");} html, table {font-family: regular; font-size: 2.8vh;}</style><table width="100%" height="100%" style="color: black;"><tr><td align="center" valign="center">'.lang('nahrávám...', 'loading...').'</td></tr></table>

}

?>
