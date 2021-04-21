<?php
session_start();

include '../fce.php';
include '../sql_open.php';

    $cat = '';
    /*
    if (isset($_GET['c'])) {
      switch($_GET['c']) {
        case 'feature-within-15': $cat = 'AND id_kat = 1'; break;
        case 'feature-above-15': $cat = 'AND id_kat = 2'; break;
        case 'animated-film': $cat = 'AND id_kat = 3'; break;
        case 'doc-exp-within-15': $cat = 'AND id_kat = 4 OR id_kat = 6'; break;
        case 'doc-exp-above-15': $cat = 'AND id_kat = 5 OR id_kat = 7';  break;
        case 'aramis-price': $cat = 'AND aramis = 1'; break;
        case 'famo-pisek': $cat = 'AND id_kat = 8'; break;
        case 'utb-zlin': $cat = 'AND id_kat = 9'; break;
        case 'retrospective': $cat = 'AND id_kat = 10'; break;
      }
    }
    */
    echo '
    <div class="filmy_legenda">
    <table>
      <tr><td><div class="embed"></div>&emsp;'.lang('Film k přehrání', 'Movie available to paly').'</td></tr>
      <tr><td><div class="soutez_'.lang('s', 'c').'"></div>&emsp;'.lang('Soutěžní kategorie', 'Competition category').'</td></tr>
      <tr><td><div class="aramis"></div>&emsp;'.lang('Aramisova cena', 'Aramis price').'</td></tr>
    </table>
    </div>
    ';

    $now = date('Y-m-d', time());
    $vcera = date('Y-m-d', strtotime("-1 days"));

    if (isset($_GET['c'])) {

        switch($_GET['c']) {
          case 'aramis-price': $cat = 'AND typ = "film" AND aramis = 1'; break;
          case 'event-recordings': $cat = 'AND typ = "event" AND embed <> ""'; break;
          case 'play-now':
            if ($_SESSION['filmoteka'] > 0) {
              $cat = 'AND (typ = "film" OR (typ = "event" AND embed <> ""))
                      AND (SELECT COUNT(*) FROM program
                      WHERE ((typ = "blok" AND id_event = event.id_blok)
                         OR (typ = "event" AND id_event = event.id))
                        AND ((event.embed <> "" AND online = "1" AND datum = "'.$vcera.'") > 0
                         OR event.embed <> "" AND online = "2" AND datum = "'.$now.'") > 0)
                         OR (typ = "event" AND embed <> "")'; break;
            } else {
              $cat = 'AND id = -1';
            }
          case 'blocks': $order = 'ORDER BY id_blok'; break;
          case 'competition': $cat = 'AND typ = "film" AND soutez = 1'; break;
          case 'out-of-competition': $cat = 'AND typ = "film" AND soutez = 2'; break;
          default: $cat = 'AND typ = "film" AND id_kat LIKE CONCAT("%|", (SELECT id FROM kategorie WHERE link = "'.$_GET['c'].'"), "|%")'; break;
        }

    }

    $cat = $cat?$cat:'AND typ = "film"';
    $order = $order?$order:'ORDER BY typ DESC, '.lang('nazev', 'nazev_en');

    $sql = 'SELECT
            typ, nazev, nazev_en, link, thumb, delka, aramis, id_kat, embed, soutez,
            (SELECT zkr FROM blok WHERE id = event.id_blok) AS zkr_blok,
            (SELECT nazev FROM blok WHERE id = event.id_blok) AS nazev_blok,
            (SELECT nazev_en FROM blok WHERE id = event.id_blok) AS nazev_blok_en,
            (SELECT COUNT(*) FROM program WHERE ((typ = "blok" AND id_event = event.id_blok) OR (typ = "event" AND id_event = event.id)) AND event.embed <> "" AND ((online = 1 AND datum = "'.$vcera.'") OR (online = 2 AND datum = "'.$now.'"))) AS k_prehrani
            FROM event
            WHERE rok = '.$_SESSION['rok'].'
            '.$cat.'
            '.$order; // WHERE (typ = "film" OR (typ = "event" AND embed <> "")) AND rok = ...
            /*
            (SELECT nazev FROM kategorie WHERE id = event.id_kat) AS nazev_kat,
            (SELECT nazev_en FROM kategorie WHERE id = event.id_kat) AS nazev_kat_en,
            */
    $filmy = mysqli_query($conn, $sql);

    $blokNow = false;

    if (mysqli_num_rows($filmy) > 0) {

        while ($film = mysqli_fetch_assoc($filmy)) {

          if ($_GET['c'] == 'blocks') {
            if ($blokNow != $film['zkr_blok']) {
              echo '<h1>'.$film['zkr_blok'].' — '.lang($film['nazev_blok'], $film['nazev_blok_en']).'<br><br></h1>';
              $blokNow = $film['zkr_blok'];
            }
          }

          if ($film['thumb']) {
            $thumb = '/data/up/s/'.$film['thumb'];
          } else {
            $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
          }

          switch ($film['typ']) {
            case 'film': $adr = '/films/film/'; break;
            case 'event': $adr = '/programme/event/'; break;
          }

          if ($film['typ'] == 'event' && !isset($typchange)) {
            echo '<hr class="hr"><h1>'.lang('Události', 'Events').'</h1>';
            $typchange = true;
          }

          echo '
          <div class="film" link="'.$adr.$film['link'].'">
            <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
            <div class="popis">
              <div class="nazev">'.lang($film['nazev'], $film['nazev_en']).'</div>
              <div class="nazev_kat">'.lang($film['nazev_kat'], $film['nazev_kat_en']);
                if (($film['k_prehrani'] && $_SESSION['filmoteka'] > 0) || ($film['typ'] == "event" && $film['embed'])) {echo '<div class="embed"></div>';}
                if ($film['soutez'] == 1 || ($film['id_kat'] > 0 && $film['id_kat'] <= 7)) {echo '<div class="soutez_'.lang('s', 'c').'"></div>';}
                if ($film['aramis'] == 1) {echo '<div class="aramis"></div>';}
              echo '</div>';
              if ($film['delka']) {
                echo '<div class="delka">'.$film['delka'].'\'</div>';
              }
              echo '
            </div>
          </div>
          ';
        }

    } else {

        echo '
        <h1>'.lang('ŽÁDNÝ ZÁZNAM', 'NO RECORD WAS FOUND').'</h1>
        ';

    }

    if (isset($_GET['c'])) {
      $link = '/category/'.$_GET['c'];
    } else {
      $link = '';
    }

      echo '
      <script>
        $(\'#content .link\').removeClass("selected");
        if ($(\'.link[link="/films'.$link.'"]\').length) {
          $(\'.link[link="/films'.$link.'"]\').addClass("selected");
        }
      </script>
      ';

include '../sql_close.php';
include '../document/lz.php';


?>
