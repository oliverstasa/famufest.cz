<?php
session_start();

if (isset($_GET['f'])) {

include '../fce.php';
include '../sql_open.php';

$now = date('Y-m-d', time());
$vcera = date('Y-m-d', strtotime("-1 days")); // včera

if ($_GET['f'] == 'play-now') {
  $sql = 'SELECT
          nazev, nazev_en, thumb, popis, popis_en, delka, aramis, embed, geoblok,
          (SELECT reklama FROM blok WHERE id = event.id_blok) AS reklama,
          (SELECT link FROM kategorie WHERE id = event.id_kat) AS link_kat
          FROM event
          WHERE typ = "film"
            AND (SELECT
                      COUNT(*) AS vysledek
                      FROM program
                      WHERE ((typ = "blok" AND id_event = event.id_blok)
                         OR (typ = "event" AND id_event = event.id))
                        AND ((online = "1" AND datum = "'.$vcera.'") > 0)
                            OR (online = "2" AND datum = "'.$now.'") > 0))';
} else {
  $sql = 'SELECT nazev, nazev_en, thumb, popis, popis_en, delka, aramis, embed, geoblok, (SELECT reklama FROM blok WHERE id = event.id_blok) AS reklama, (SELECT link FROM kategorie WHERE id = event.id_kat) AS link_kat FROM event WHERE typ = "film" AND link = "'.$_GET['f'].'"';
}
$filmy = mysqli_query($conn, $sql);


// jestli film existuje, vypiš...
////////////////////////////////////////////////////////////////////////////////

if (mysqli_num_rows($filmy) > 0) {

  $film = mysqli_fetch_assoc($filmy);

  // jestli se film promita
  //////////////////////////////////////////////////////////////////////////////

  if ($film['embed'] != '' && $_SESSION['filmoteka'] > 0) {
    //$cas = date('H:i', time());
    $video = 'SELECT
              COUNT(*) AS vysledek
              FROM program
              WHERE ((typ = "blok" AND id_event = (SELECT id_blok FROM event WHERE link = "'.$_GET['f'].'"))
                    OR (typ = "event" AND id_event = (SELECT id FROM event WHERE link = "'.$_GET['f'].'")))
              AND ((online = "1" AND datum = "'.$vcera.'")
                  OR (online = "2" AND datum = "'.$now.'"))';
    $promitat = mysqli_query($conn, $video);
    if (mysqli_num_rows($promitat) > 0) {
        $r = mysqli_fetch_assoc($promitat);
        if ($r['vysledek'] > 0 && $_SESSION['filmoteka'] > 0) {
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
                }, '.($do_konce+9000*1000).'); // 9000s=>15min tolerance
              </script>

              ';
              
              if ($film['reklama']) {
                echo '<div id="playlist" class="profilm">
                      <div id="playPlaylist" class="profilm">
                        <div class="playText"><div class="playBtn"></div><br>'.lang('PŘEHRÁT FILM', 'PLAY FILM').'</div>
                      </div>
                      <div class="showreelFilmu profilm" style="background: url(\'/data/up/s/'.$film['thumb'].'\') no-repeat center center; background-size: cover;"></div>
                      <iframe src="/data/silence.mp3" type="audio/mp3" allow="autoplay" id="audio" style="display:none"></iframe>
                      <div class="ffspot"><video autoplay id="spotik"><source src="/data/up/'.$film['reklama'].'" type="video/mp4"></video></div>';
              }
              
              echo '<table class="film_embed'; if ($film['reklama']) {echo ' vimeoPlaylist';} echo '"><tr><td>';

                // POKUD YOUTUBE
                if (strpos($film['embed'], 'youtube') !== false || strpos($film['embed'], 'youtu.be') !== false) {

                  if (strripos($film['embed'], '?v=') !== false) {
                    $videolink = substr($film['embed'], strripos($film['embed'], '?v=')+3);
                  } else if (strripos($film['embed'], '.be/') !== false) {
                    $videolink = substr($film['embed'], strripos($film['embed'], '.be/')+4);
                  }

                  if (isset($videolink)) {
                  echo '
                    <iframe class="vimeo" type="text/html" src="https://www.youtube.com/embed/'.$videolink.'?autoplay=1" frameborder="0"></iframe>
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

                if ($film['reklama']) {
                  echo '</div>';
                }

              echo '
              </td></tr><tr><td>
              '.lang('Film je dostupný k přehrání do dnešní půlnoci', 'Movie available to play until midnight').'
              </td></tr></table>
              ';
              }

        }

    }
  }

  // background
  //////////////////////////////////////////////////////////////////////////////

  if ($film['thumb']) {
    $thumb = '/data/up/s/'.$film['thumb'];
  } else {
    $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
  }

  if ($film['thumb']) {
  echo '
  <style>
    body {background: transparent;}
    .fullscreen_thumb {background: url(\''.$thumb.'\') center center; background-size: cover;}
  </style>
  ';
  }

  // označení kategorie filmu
  //////////////////////////////////////////////////////////////////////////////

  /*
  switch($film['link_kat']) {
    case 'document-within-15': case 'experiment-within-15': $film['link_kat'] = 'doc-exp-within-15'; break;
    case 'document-above-15': case 'experiment-above-15': $film['link_kat'] = 'doc-exp-above-15'; break;
  }
  */

  echo '
  <script>
    $(\'#content .link\').removeClass("selected");
    $(\'.link[link="/films/category/'.$film['link_kat'].'"]'; if ($film['aramis'] == 1) {echo ', .link[link="/films/category/aramis-price"]';} echo '\').addClass("selected");
  </script>
  ';

  // výpis informací o filmu
  //////////////////////////////////////////////////////////////////////////////

  $x = 0;
  $popis_filmu = '';
  // $popis = nl2br(lang($film['popis'], $film['popis_en']));
  $popis = lang($film['popis'], $film['popis_en']);
  $popis = str_replace("<3", "💜", $popis);
  if (strpos($popis, ".....") !== false) {
    $istable = 0;
    $popis_arr = preg_split("/((\r?\n)|(\r\n?))/", $popis);
    $limit = sizeof($popis_arr);
    foreach($popis_arr as $line) {
      $x++;

      if (strpos($line, ".....") !== false) {

        if ($istable == 0) {
          $popis_filmu .= '
          <table class="stab">';
          $istable = 1;
        }

        $vals = explode(".....", $line);
        $popis_filmu .= '
        <tr><td>'.$vals[0].'</td><td>'.$vals[1].'</td></tr>';

        if ($x == $limit) {
          $popis_filmu .= '
          </table>';
          $istable = 0;
        }

      } else {

        if ($istable == 1) {
          $popis_filmu .= '
          </table>';
          $istable = 0;
        }

        $popis_filmu .= $line.'<br>';

      }

    }

  } else {
    $popis_filmu = nl2br($popis);
  }

  $popis_filmu = str_replace("DIRECOR", "DIRECTOR", $popis_filmu);

  // kdy a kde se film promítá
  //////////////////////////////////////////////////////////////////////////////

  echo '
  <h1>'.lang($film['nazev'], $film['nazev_en']).'</h1>
  <img class="fullthumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
  <div class="fullpopis">
    <h2>'.lang('Délka', 'Length').': '.$film['delka'].'\'</h2>
    <p>'.$popis_filmu.'</p>
  </div>
  ';

  $dny = mysqli_query($conn, 'SELECT
                              typ, datum, zacatek, konec,
                              (SELECT nazev FROM venue WHERE id = program.id_venue) AS nazev,
                              (SELECT nazev_en FROM venue WHERE id = program.id_venue) AS nazev_en,
                              (SELECT link FROM venue WHERE id = program.id_venue) AS venue_link,
                              (SELECT zkr FROM blok WHERE id = (SELECT event.id_blok FROM event WHERE event.link = "'.$_GET['f'].'")) AS zkr,
                              (SELECT link FROM blok WHERE id = (SELECT event.id_blok FROM event WHERE event.link = "'.$_GET['f'].'")) AS blok_link
                              FROM program
                              WHERE (typ = "blok" AND id_event = (SELECT id_blok FROM event WHERE link = "'.$_GET['f'].'"))
                                 OR (typ = "event" AND id_event = (SELECT id FROM event WHERE link = "'.$_GET['f'].'"))
                              ORDER BY datum');

  if (mysqli_num_rows($dny) > 0) {

    echo '
    <h1>'.lang('V programu', 'In programme').'</h1>
    ';

    while ($den = mysqli_fetch_assoc($dny)) {


        if (isset($den['zkr']) && $den['typ'] == 'blok') {
          $blok_zkr = $den['zkr'].' • ';
          $link_blok = '/programme/block/'.$den['blok_link'];
        } else {
          $blok_zkr = '';
          $link_blok = '/films/film/'.$_GET['f'];
        }
        $link_day = '/programme/day/'.$den['datum'];
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

        // <div class="link" link="'.$link_day.'">'.$day.' '.$day_date.', '.$cas_zacatek.'—'.$cas_konec.' ('.$den['zkr'].'), '.lang($den['nazev'], $den['nazev_en']).'</div> // jeden link jen
        echo '
        <div class="link_list">
          <div class="link" link="'.$link_venue.'">'.lang($den['nazev'], $den['nazev_en']).'</div>
          <div class="link" link="'.$link_blok.'">'.$blok_zkr.$cas_zacatek.'—'.$cas_konec.'</div>
          <div class="link" link="'.$link_day.'">'.$day.' '.$day_date.'</div>
        </div>
        <br><br><br>';

    }

  }


  // konec vypisu filmu
  //////////////////////////////////////////////////////////////////////////////

} else {

  echo '<h1>'.lang('FILM NEEXISTUJE', 'FILM DOESN\'T EXIST').'</h1>';

}

include '../sql_close.php';
include '../document/lz.php';

}

?>
