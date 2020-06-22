<?php
session_start();

  include '../sql_open.php';
  include '../fce.php';

  if (isset($_GET['d'])) {

    if ($_GET['d'] == 'all') {
      $day = '';
    } else {
      $day = ' AND datum = "'.$_GET['d'].'"';
    }

  } else {

    $day = ' ';

  }

  // VENUE
  $program = mysqli_query($conn, 'SELECT
                                  distinct
                                  (SELECT nazev FROM venue WHERE id = program.id_venue) AS nazev,
                                  (SELECT nazev_en FROM venue WHERE id = program.id_venue) AS nazev_en,
                                  (SELECT id FROM venue WHERE id = program.id_venue) AS id,
                                  (SELECT color FROM venue WHERE id = program.id_venue) AS color,
                                  (SELECT adresa FROM venue WHERE id = program.id_venue) AS adresa
                                  FROM program
                                  WHERE rok = '.$_SESSION['rok'].$day.' ORDER BY id DESC');

  if (mysqli_num_rows($program) > 0) {

      $row_num = 0;
      $now = date('Y-m-d', time());
      $vcera = date('Y-m-d', strtotime("-1 days"));
      while ($venue = mysqli_fetch_assoc($program)) {

        echo '
        <style>
        .bg'.str_replace("#", "", $venue['color']).' {background: #'.$venue['color'].' !important; color: white !important;}
        .bg'.str_replace("#", "", $venue['color']).':hover {color: var(--elem-color) !important;}
        </style>
        ';

        $adresa = $venue['adresa'];
        $odkaz_venue = 'https://www.google.com/maps/search/'.$adresa;
        echo '
        <h1'; if ($row_num > 0) {echo ' class="consec_h1"';} echo '>'.lang($venue['nazev'], $venue['nazev_en']); echo '<span class="adresa"><a href="'.$odkaz_venue.'" target="_blank">'.$adresa.'</a></span></h1>
        <div class="link_list">
        ';

            // VYPIS PROGRAMU NA TE VENUE
            $eventy = mysqli_query($conn, 'SELECT
                                           typ, id_event, zacatek, konec, datum, id_event AS id_for_test,
                                           (SELECT COUNT(*)
                                                   FROM program
                                                           WHERE ((typ = "blok" AND online = "1" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok = id_for_test) > 0)
                                                              OR (typ = "event" AND online = "1" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id = id_for_test) > 0)
                                                              OR (typ = "blok" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok = id_for_test) > 0)
                                                              OR (typ = "event" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id = id_for_test)))) AS k_prehrani
                                           FROM program
                                           WHERE rok = '.$_SESSION['rok'].''.$day.' AND id_venue = '.$venue['id'].'
                                           ORDER BY datum, zacatek');

            while ($event = mysqli_fetch_assoc($eventy)) {

              $addon = '';

              if (!isset($this_date)) {
                $this_date = $event['datum'];
              }

              if ($this_date != $event['datum']) {
                echo '
                </div>
                <div class="link_list">
                ';
                $this_date = $event['datum'];
              }

              if ($event['typ'] == 'event') {$addon = 'typ, ';} else {$addon = 'zkr, ';}
              $jmeno = mysqli_query($conn, 'SELECT  '.$addon.'nazev, nazev_en, link FROM '.$event['typ'].' WHERE id = '.$event['id_event']);
              $nazev = mysqli_fetch_assoc($jmeno);

              $begin = explode(" ", $event['zacatek'])[1];
              $end = explode(" ", $event['konec'])[1];

              $cas_zacatek = substr($begin, 0, 5);
              $cas_konec = substr($end, 0, 5);
              $cas_zacatek = str_replace(":", ".", $cas_zacatek);
              $cas_konec = str_replace(":", ".", $cas_konec);

              $multiplier = 15; // vh
              $sirka = (str_replace(':', '.', $cas_konec) - str_replace(':', '.', $cas_zacatek)) * $multiplier;

              echo '<div class="link blok bg'.str_replace("#", "", $venue['color']).'" style="width: '.$sirka.'vh;" link="/';
                switch ($event['typ']) {
                  case 'blok': echo 'programme/block'; break;
                  case 'event':
                    switch($nazev['typ']) {
                      case 'film': echo 'films/film'; break;
                      case 'event': echo 'programme/event'; break;
                    }
                  break;
                  default: echo 'programme'; break;
                }
              echo '/'.$nazev['link'].'"><h2>';

                // if ($event['k_prehrani'] > 0 && $_SESSION['filmoteka'] > 0) {echo '<div class="toplay '.$_SESSION['daytime'].'"></div>&nbsp;';}

                if ($event['typ'] == 'blok') {echo $nazev['zkr'].' — ';}

                echo lang($nazev['nazev'], $nazev['nazev_en']); echo '</h2><div class="time">';

                if (!isset($_GET['d']) || $_GET['d'] == 'all') {
                  $den = datumtoday($event['datum']);
                      $date = explode('-', $event['datum']);
                      $day_date = ($date[2]*1).'. '.($date[1]*1).'.';
                  echo $den.' '.$day_date.' • ';
                }

              echo $cas_zacatek.'—'.$cas_konec.'</div></div>

              ';

            }

            echo '</div>';

      $row_num++;
      }

  } else {

    echo '<h1>'.lang('ŽÁDNÝ PROGRAM', 'NO PROGRAMME').'</h1>';

  }

?>
