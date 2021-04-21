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
                                 nazev, nazev_en, zkr, playlist, reklama, podcast
                                 FROM blok
                                 WHERE link = "'.$_GET['b'].'"');

    if (mysqli_num_rows($program) > 0) {

      $necohraje = mysqli_query($conn, 'SELECT COUNT(*) AS k_prehrani FROM program WHERE typ = "blok" AND id_event = (SELECT id FROM blok WHERE link = "'.$_GET['b'].'") AND ((online = 1 AND datum = "'.$vcera.'") OR (online = 2 AND datum = "'.$now.'"))');
      $hrajeme = mysqli_fetch_assoc($necohraje);  

      $muzehrat = $hrajeme['k_prehrani'];

      $blok_sql = mysqli_fetch_assoc($blok);
      echo '<h1>'.$blok_sql['zkr'].' — '.lang($blok_sql['nazev'], $blok_sql['nazev_en']).'</h1>';

      if ($blok_sql['reklama'] && $blok_sql['playlist'] && $muzehrat > 0) {

        $prgThumb = mysqli_query($conn, 'SELECT thumb FROM event WHERE id_blok = (SELECT id FROM blok WHERE link = "'.$_GET['b'].'") ORDER BY RAND()');

        echo '<div id="playlist"><div id="playPlaylist"><div class="playText"><div class="playBtn"></div><br>'.lang('PŘEHRÁT FILMY', 'PLAY FILMS').'</div></div><div class="showreelFilmu" data-thumbs="';

        $imgs = array();
        while ($evt = mysqli_fetch_assoc($prgThumb)) {
          if ($evt['thumb']) {
            array_push($imgs, $evt['thumb']);
            $lastThumb = $evt['thumb'];
          }
        }

        echo join(',', $imgs);
        
        echo '" style="background: url(\'/data/up/s/'.$lastThumb.'\') no-repeat center center; background-size: cover;"></div>';
        echo '<iframe src="/data/silence.mp3" type="audio/mp3" allow="autoplay" id="audio" style="display:none"></iframe>';
        echo '<div class="ffspot"><video autoplay id="spotik"><source src="/data/up/'.$blok_sql['reklama'].'" type="video/mp4"></video></div>';
        echo '<div class="vimeoPlaylist"><iframe class="vimeo" src="'.$blok_sql['playlist'].'/embed" allowfullscreen frameborder="0"></iframe></div>';
        echo '</div>';

      } else if ($blok_sql['playlist'] && $muzehrat) {

        echo $blok_sql['playlist'].'<br><br>';

      }

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

      
      
      if ($blok_sql['podcast'] && strpos($blok_sql['podcast'], 'soundcloud.com') !== false) {

        //$link = substr($blok_sql['podcast'], strpos($blok_sql['podcast'], 'soundcloud.com/')+15);

        echo '<br><br><h1>'.lang('Podcast', 'Podcast').'</h1><br>'
        //.str_replace('width="100%"', 'style="width: 60vh"', $blok_sql['podcast']).
        .'<iframe style="width: 60vh; height: 20vh" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url='.$blok_sql['podcast'].'"></iframe>'.
        '<br>';

      }



    } else {

      echo '<h1>ŽÁDNÝ ZÁZNAM</h1>';

    }

    $now = date('Y-m-d H:i:s', time());  
    $lista = mysqli_query($conn, 'SELECT lista, lista_en FROM lista WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'" LIMIT 1');

    if (mysqli_num_rows($lista) > 0) {
      $ls = mysqli_fetch_assoc($lista);
      echo '<h1 class="reklamnilista">'.lang($ls['lista'], $ls['lista_en']).'</h1>';
    }

    include '../sql_close.php';
    include '../document/lz.php';

}

?>
