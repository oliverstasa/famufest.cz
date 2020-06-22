<?php
session_start();

if (isset($_GET['b'])) {

    include '../sql_open.php';
    include '../fce.php';

    $now = date('Y-m-d', time());
    $vcera = date('Y-m-d', strtotime("-1 days"));

    // VENUE
    $program = mysqli_query($conn, 'SELECT
                                    typ, nazev, nazev_en, link, thumb, delka, aramis, id_kat,
                                    (SELECT nazev FROM kategorie WHERE id = event.id_kat) AS nazev_kat,
                                    (SELECT nazev_en FROM kategorie WHERE id = event.id_kat) AS nazev_kat_en,
                                    (SELECT COUNT(*) FROM program WHERE ((typ = "blok" AND id_event = event.id_blok) OR (typ = "event" AND id_event = event.id)) AND event.embed <> "" AND ((online = 1 AND datum = "'.$vcera.'") OR (online = 2 AND datum = "'.$now.'"))) AS k_prehrani
                                    FROM event
                                    WHERE id_blok = (SELECT id FROM blok WHERE link = "'.$_GET['b'].'")
                                    ORDER BY '.lang('nazev', 'nazev_en'));

    $blok = mysqli_query($conn, 'SELECT
                                 nazev, nazev_en, zkr
                                 FROM blok
                                 WHERE link = "'.$_GET['b'].'"');

    if (mysqli_num_rows($program) > 0) {

      $blok_sql = mysqli_fetch_assoc($blok);
      echo '<h1>'.$blok_sql['zkr'].' — '.lang($blok_sql['nazev'], $blok_sql['nazev_en']).'</h1>';

      while ($event = mysqli_fetch_assoc($program)) {

          switch ($event['typ']) {
            case 'event': $linker = 'programme/event'; break;
            case 'film': $linker = 'films/film'; break;
          }

          if ($event['thumb']) {
            $thumb = '/data/up/s/'.$event['thumb'];
          } else {
            $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
          }

          echo '
          <div class="film" link="/'.$linker.'/'.$event['link'].'">
            <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
            <div class="popis">
              <div class="nazev">'.lang($event['nazev'], $event['nazev_en']).'</div>
              <div class="nazev_kat">'.lang($event['nazev_kat'], $event['nazev_kat_en']);
                if ($event['k_prehrani'] && $_SESSION['filmoteka'] > 0) {echo '<div class="embed"></div>';}
                if ($event['id_kat'] > 0 && $event['id_kat'] <= 7) {echo '<div class="soutez_'.lang('s', 'c').'"></div>';}
                if ($event['aramis'] == 1) {echo '<div class="aramis"></div>';}
              echo '</div>
              <div class="delka">'.$event['delka'].'\'</div>
            </div>
          </div>
          ';

      }



      $dny = mysqli_query($conn, 'SELECT
                                  datum, zacatek, konec,
                                  (SELECT nazev FROM venue WHERE id = program.id_venue) AS nazev,
                                  (SELECT nazev_en FROM venue WHERE id = program.id_venue) AS nazev_en
                                  FROM program
                                  WHERE typ = "blok" AND id_event = (SELECT id FROM blok WHERE link = "'.$_GET['b'].'")
                                  ORDER BY datum');

      if (mysqli_num_rows($dny) > 0) {

        echo '
        <h1>'.lang('V programu', 'In programme').'</h1>
        <div class="link_list">';

        while ($den = mysqli_fetch_assoc($dny)) {

            $link = '/programme/day/'.$den['datum'];

            $day = datumtoday($den['datum']);
                $date = explode('-', $den['datum']);
                $day_date = ($date[2]*1).'. '.($date[1]*1).'.';

            $begin = explode(" ", $den['zacatek'])[1];
            $end = explode(" ", $den['konec'])[1];

            $cas_zacatek = substr($begin, 0, 5);
            $cas_konec = substr($end, 0, 5);
            $cas_zacatek = str_replace(":", ".", $cas_zacatek);
            $cas_konec = str_replace(":", ".", $cas_konec);

            echo '
            <div class="link" link="'.$link.'">'.lang($den['nazev'], $den['nazev_en']).' • '.$cas_zacatek.'—'.$cas_konec.' • '.$day.' '.$day_date.'</div>';

        }

        echo '</div>';

      }



    } else {

      echo '<h1>ŽÁDNÝ ZÁZNAM</h1>';

    }

    include '../sql_close.php';
    include '../document/lz.php';

}

?>
