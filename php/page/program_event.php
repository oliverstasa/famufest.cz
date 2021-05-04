<?php
session_start();

if (isset($_GET['e'])) {

include '../fce.php';
include '../sql_open.php';

$sql = 'SELECT nazev, nazev_en, thumb, popis, popis_en, delka, embed, geoblok FROM event WHERE typ = "event" AND link = "'.$_GET['e'].'"';
$filmy = mysqli_query($conn, $sql);

if (mysqli_num_rows($filmy) > 0) {

  $film = mysqli_fetch_assoc($filmy);

  if ($film['thumb']) {
    echo '
    <style>
      body {background: transparent;}
      .fullscreen_thumb {background: url(\'/data/up/'.$film['thumb'].'\') center center; background-size: cover;}
    </style>
    ';
  }

  //if ($_SESSION['filmoteka'] > 0) {
  if ($film['embed']) {
      $do_konce = strtotime('tomorrow')-time();

        $lokace = country();
        if ($film['geoblok'] == 1 && $lokace != 'CZ') {
          echo '
          <h1 class="geoblok">'.lang('Film ve Vaší zemi není dostupný', 'This movie is not available for your country').'</h1>
          <audio autoplay style="display: none;"><source src="/data/sad_trombone.mp3" type="audio/mpeg"></audio>
          ';
        } else {
        echo '
        <script>
          setTimeout(function(){
            window.location.reload();
          }, '.($do_konce*1000).');
        </script>
        <table class="film_embed"><tr><td>';

          // POKUD YOUTUBE
          if (strpos($film['embed'], 'youtube') !== false || strpos($film['embed'], 'youtu.be') !== false) {

            if (strripos($film['embed'], '?v=') !== false) {
              $videolink = substr($film['embed'], strripos($film['embed'], '?v=')+3);
            } else if (strripos($film['embed'], '.be/') !== false) {
              $videolink = substr($film['embed'], strripos($film['embed'], '.be/')+4);
            }

            if (isset($videolink)) {
            echo '
              <iframe class="vimeo" type="text/html" src="https://www.youtube.com/embed/'.$videolink.'?autoplay=1" frameborder="0" allowfullscreen></iframe>
            ';
            } else {
              echo lang('CHYBA PŘI NAČÍTÁNÍ VIDEA', 'ERROR OCCURED WHILE LOADING VIDEO');
            }

          // POKUD VIMEO
          } else if (strpos($film['embed'], 'vimeo') !== false) {

            if (strpos($film['embed'], '/') !== false) {
              $videolink = substr($film['embed'], strripos($film['embed'], '/')+1);
            }

            if (isset($videolink)) {
            echo '
              <script type="text/javascript" src="https://player.vimeo.com/api/player.js"></script>
              <iframe class="vimeo" src="https://player.vimeo.com/video/'.$videolink.'" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            ';
            } else {
              echo lang('CHYBA PŘI NAČÍTÁNÍ VIDEA', 'ERROR OCCURED WHILE LOADING VIDEO');
            }

          } else {
            echo lang('CHYBA PŘI NAČÍTÁNÍ VIDEA', 'ERROR OCCURED WHILE LOADING VIDEO');
          }

        echo '
        </td></tr><tr><td>
        '.lang('Video je dostupné k přehrání po neomezenou dobu', 'Video available to play for unlimited time').'
        </td></tr></table>
        ';
        }

    }
  //}

  if ($film['thumb']) {
    $thumb = '/data/up/'.$film['thumb'];
  } else {
    $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
  }

  $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';

  $popis = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $film['popis']);
  $popis_en = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $film['popis_en']);

  echo '
  <h1>'.lang($film['nazev'], $film['nazev_en']).'</h1>
  <img class="fullthumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
  <div class="fullpopis">
    <p>'.nl2br(lang($popis, $popis_en)).'</p>
  </div>
  ';

  $dny = mysqli_query($conn, 'SELECT
                              datum, zacatek, konec,
                              (SELECT nazev FROM venue WHERE id = program.id_venue) AS nazev,
                              (SELECT nazev_en FROM venue WHERE id = program.id_venue) AS nazev_en,
                              (SELECT link FROM venue WHERE id = program.id_venue) AS venue_link,
                              (SELECT zkr FROM blok WHERE id = (SELECT event.id_blok FROM event WHERE event.link = "'.$_GET['e'].'")) AS zkr,
                              (SELECT link FROM blok WHERE id = (SELECT event.id_blok FROM event WHERE event.link = "'.$_GET['e'].'")) AS blok_link
                              FROM program
                              WHERE (typ = "blok" AND id_event = (SELECT id_blok FROM event WHERE link = "'.$_GET['e'].'"))
                                 OR (typ = "event" AND id_event = (SELECT id FROM event WHERE link = "'.$_GET['e'].'"))
                              ORDER BY datum');

  if (mysqli_num_rows($dny) > 0) {

    echo '
    <h1>'.lang('V programu', 'In programme').'</h1>
    ';

    while ($den = mysqli_fetch_assoc($dny)) {

        $link_day = '/programme/day/'.$den['datum'];

        if (isset($den['zkr'])) {
          $blok_zkr = $den['zkr'].' • ';
          $link_blok = '/programme/block/'.$den['blok_link'];
        } else {
          $blok_zkr = '';
          $link_blok = '/programme/event/'.$_GET['e'];
        }

        //$link_venue = '/programme/venue/'.$den['venue_link'];
        $link_venue = '/programme';

        $day = datumtoday($den['datum']);
            $date = explode('-', $den['datum']);
            $day_date = ($date[2]*1).'. '.($date[1]*1).'.';

        $begin = explode(" ", $den['zacatek'])[1];
        $end = explode(" ", $den['konec'])[1];

        $cas_zacatek = substr($begin, 0, 5);
        $cas_konec = substr($end, 0, 5);
        $cas_zacatek = str_replace(":", ".", $cas_zacatek);
        $cas_konec = str_replace(":", ".", $cas_konec);

        // <div class="link" link="'.$link_day.'">'.$day.' '.$day_date.', '.$cas_zacatek.'—'.$cas_konec.' ('.$den['zkr'].'), '.lang($den['nazev'], $den['nazev_en']).'</div>
        echo '
        <div class="link_list">
          <div class="link" link="'.$link_venue.'">'.lang($den['nazev'], $den['nazev_en']).'</div>
          <div class="link" link="'.$link_blok.'">'.$blok_zkr.$cas_zacatek.'—'.$cas_konec.'</div>
          <div class="link" link="'.$link_day.'">'.$day.' '.$day_date.'</div>
        </div>';

    }

  }

} else {

  echo '<h1>'.lang('UDÁLOST NEEXISTUJE', 'EVENT DOESN\'T EXIST').'</h1>';

}

include '../sql_close.php';
include '../document/lz.php';

}

?>
