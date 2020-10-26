<?php
  $uri = $_SERVER['REQUEST_URI'];
?>
<!doctype html>
<html>
  <?php echo '<head kino="'.$_SESSION['kino'].'" filmoteka="'.$_SESSION['filmoteka'].'" daytime="'.$_SESSION['daytime'].'">'; ?>

    <meta charset="utf-8">

    <title><?php include './php/title.php'; ?></title>
    <meta name="keywords" content="famufest, famu, festival, film, praha, soutěž, projekce">
    <meta name="description" content="Soutěžní festival kombinující projekce snímků studentů FAMU za loňský rok s doprovodným programem, jako jsou workshopy, koncerty, výstavy nebo předčítání scénářů.">

    <meta name="robots" content="all">
    <meta name="author" content="Oliver Staša — Developer a webmaster, Filip Kopecký — Grafická koncepce a design, Emma Folprechtová — konzultace">
    <meta name="viewport" content="width=device-width">

    <link rel="icon" href="/data/fav_<?php echo $_SESSION['daytime']; ?>.png">
    <link href="/css/main.css?v=2.33" rel="stylesheet">
    <link href="/css/<?php echo $_SESSION['daytime']; ?>.css" rel="stylesheet">

    <script src="/js/jq.js" type="text/javascript"></script>
    <script src="/js/fce.js?v=2.21" type="text/javascript"></script>

    <?php include './php/og_image.php'; ?>

  </head>
  <body>
  <?php

  include './php/sql_open.php';

  $now = date('Y-m-d', time());
  $vcera = date('Y-m-d', strtotime("-1 days"));
  $sql = 'SELECT
          (SELECT COUNT(*) FROM program WHERE datum = "'.date('Y-m-d').'") AS program,
          (SELECT COUNT(*)
                  FROM program
                  WHERE (typ = "blok" AND online = "1" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$vcera.'")) > 0)
                     OR (typ = "event" AND online = "1" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$vcera.'")) > 0)
                     OR (typ = "blok" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$now.'")) > 0)
                     OR (typ = "event" AND online = "2" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$now.'")) > 0)) AS filmoteka,
          (SELECT COUNT(*) FROM event WHERE embed <> "") AS k_prehrani,
          (SELECT COUNT(*)
                  FROM kino
                  WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'") AS kino,
          (SELECT termin FROM rok WHERE active = 1) as termin,
          nazev, nazev_en, link
          FROM news ORDER BY id DESC LIMIT 1
          ';
  $day_ress = mysqli_query($conn, $sql);
  $def = mysqli_fetch_assoc($day_ress); // $jeden

  if ($def['program'] != 0) { // $jeden['COUNT(*)']
    $link_program = '/programme/day/'.date('Y-m-d');
  } else {
    $link_program = '/programme/day/all';
  }

  /*
  echo '
  <div id="archiv">ARCHIV';

  echo '<form id="rok"><select>';

    $sql = 'SELECT rok FROM rok ORDER BY rok DESC';
    $rok_ress = mysqli_query($conn, $sql);
    if (mysqli_num_rows($rok_ress) > 0) {
      while ($roky = mysqli_fetch_assoc($rok_ress)) {
        echo '<option value="'.$roky['rok'].'"'; if ($roky['rok'] == $_SESSION['rok']) {echo ' selected';} echo '>'.$roky['rok'].'</option>'; // date('Y')
      }
    }

  echo '</select></form>';

  echo '</div>
  ';
  */

  if ($def['link']) {
    echo '
    <div class="link'; if ($uri != '/') {echo ' hidden';} echo '" id="novinka" link="/news/'.$def['link'].'">
      <div class="slider">
        <div>'.lang($def['nazev'], $def['nazev_en']).'</div>
      </div>
    </div>
    ';
  }

  echo '
  <ul id="menu"'; if ($uri != '/') {echo ' class="page"';} echo '>
    ';

    if ($_SESSION['kino'] > 0 && $def['kino'] > 0) {echo '<li link="/live" class="live">'.lang('ŽIVĚ', 'LIVE').' <div class="toplay"></div></li>';}

    echo '
    <li link="'.$link_program.'" id="prg">'.lang('PROGRAM', 'PROGRAMME').'</li>
    ';

    if ($_SESSION['filmoteka'] > 0 && $def['filmoteka'] > 0) {
      echo '<li link="/films/category/play-now" id="flm">'.lang('FILMOTÉKA', 'FILM LIBRARY').' <div class="toplay"></div></li>';
    } else {
      echo '<li link="/films" id="flm">'.lang('FILMY', 'FILMS').'</li>';
    }

    /*

    <!-- <li link="/visitors">'.lang('PRO NÁVŠTĚVNÍKY', 'FOR VISITORS').'</li> -->
    <!-- <li link="/places">'.lang('MÍSTA', 'PLACES').'</li> -->

    */

    echo '
    <li link="/news">'.lang('NOVINKY', 'NEWS').'</li>
    <li link="/about">'.lang('O FESTIVALU', 'FESTIVAL').'</li>
    <li link="/gallery">'.lang('GALERIE', 'GALLERY').'</li>
    <li class="go_read">'.lang('ČÍTÁRNA', 'READING ROOM').'</li>
    <li link="/partners">'.lang('PARTNEŘI', 'PARTNERS').'</li>
    <li link="/contact">'.lang('KONTAKTY', 'CONTACTS').'</li>
    <!--
    <li id="archive"><form><select id="archiverok">';

    $sql = 'SELECT
            rok
            FROM rok
            WHERE publikovano = 1
            ORDER BY rok';
    $roky = mysqli_query($conn, $sql);

      while ($rok = mysqli_fetch_assoc($roky)) {
        echo '<option value="'.$rok['rok'].'"'; if ($rok['rok'] == $_SESSION['rok']) {echo ' selected';} echo '>'.$rok['rok'].'</option>';
      }

    echo '</select></form>'.lang('ARCHIV', 'ARCHIVE').'</li>
    -->
    <li link="/lang">'.lang('EN', 'CZ').'</li>
  </ul>

  <div id="ff"'; if ($uri != '/') {echo ' class="page"';} echo '>'.$def['termin'].'</div>
  <div id="hamburgr">☰</div>
  <div id="pg"></div>
  ';


  include './php/sql_close.php';

  ?>

</body>
</html>
