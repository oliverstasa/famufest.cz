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
    <link href="/css/main.css?v=2.34" rel="stylesheet">
    <link href="/css/<?php echo $_SESSION['daytime']; ?>.css" rel="stylesheet">

    <script src="/js/jq.js" type="text/javascript"></script>
    <script src="/js/fce.js?v=2.32" type="text/javascript"></script>

    <?php include './php/og_image.php'; ?>

  </head>
  <body>
  <?php

  include './php/sql_open.php';

  $now = date('Y-m-d', time());
  $ymdhis = date('Y-m-d H:i:s', time());
  $vcera = date('Y-m-d', strtotime("-1 days"));
  $rok = $_SESSION['rok'];
  $sql = 'SELECT
          (SELECT COUNT(*) FROM program WHERE datum = "'.date('Y-m-d').'") AND rok = "'.$rok.'" AS program,
          (SELECT COUNT(*)
                  FROM program
                  WHERE (typ = "blok" AND online = "1" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$vcera.'")) > 0)
                     OR (typ = "event" AND online = "1" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$vcera.'")) > 0)
                     OR (typ = "blok" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$now.'")) > 0)
                     OR (typ = "event" AND online = "2" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$now.'")) > 0)) AS filmoteka,
          (SELECT COUNT(*) FROM event WHERE embed <> "") AS k_prehrani,
          (SELECT COUNT(*)
                  FROM kino
                  WHERE cas_od < "'.$ymdhis.'" AND cas_do > "'.$ymdhis.'") AS kino,
          (SELECT COUNT(*)
                  FROM entrypage
                  WHERE cas_od < "'.$ymdhis.'" AND cas_do > "'.$ymdhis.'" AND rok = "'.$rok.'") AS entrypage,
          (SELECT termin FROM rok WHERE rok = "'.$rok.'") AS termin,
          (SELECT COUNT(*) FROM event WHERE rok = "'.$rok.'" AND typ = "film") AS jsoufilmy,
          (SELECT COUNT(*) FROM about WHERE rok = "'.$rok.'") AS ofestu,
          (SELECT COUNT(*) FROM contacts WHERE rok = "'.$rok.'") AS kontakt,
          (SELECT COUNT(*) FROM partneri WHERE rok = "'.$rok.'") AS partneri,
          (SELECT COUNT(*) FROM news WHERE rok = "'.$rok.'") AS jsounovinky,
          (SELECT COUNT(*) FROM program WHERE rok = "'.$rok.'") AS jeprogram,
          (SELECT rok FROM rok WHERE active = 1) AS actrok,
          nazev, nazev_en, link
          FROM news ORDER BY id DESC LIMIT 1
          ';
  $day_ress = mysqli_query($conn, $sql);
  $def = mysqli_fetch_assoc($day_ress); // $jeden

  if ($def['program'] != 0 && $def['actrok'] == $rok) { // $jeden['COUNT(*)']
    $link_program = '/programme/day/'.date('Y-m-d');
  } else {
    $link_program = '/programme/day/all';
  }

  if ($def['link']) {
    echo '
    <div class="link'; if ($uri != '/') {echo ' hidden';} echo '" id="novinka" link="/news/'.$def['link'].'">
      <div class="slider">
        <div>'.lang($def['nazev'], $def['nazev_en']).'</div>
      </div>
    </div>
    ';
  }

  /*
  MENU HERE
  */

  echo '
  <ul id="menu"'; if ($uri != '/') {echo ' class="page"';} echo '>
    ';

    if ($_SESSION['kino'] > 0 && $def['kino'] > 0) {
    echo '
    <li link="/live" class="live">'.lang('ŽIVĚ', 'LIVE').' <div class="toplay"></div></li>';
    }

    if ($def['entrypage'] != 0) {
    echo '
    <li link="/submit">'.lang('PŘIHLÁSIT DÍLO', 'SUBMIT WORK').'</li>';
    }

    if ($def['jeprogram'] != 0) {
    echo '
    <li link="'.$link_program.'" id="prg">'.lang('PROGRAM', 'PROGRAMME').'</li>';
    }


    if ($_SESSION['filmoteka'] > 0 && $def['filmoteka'] > 0) {
      echo '<li link="/films/category/play-now" id="flm">'.lang('FILMOTÉKA', 'FILM LIBRARY').' <div class="toplay"></div></li>';
    } else {
    if ($def['jsoufilmy'] != 0) {
    echo '
    <li link="/films" id="flm">'.lang('FILMY', 'FILMS').'</li>';
    }
    }

    /*
    <!-- <li link="/visitors">'.lang('PRO NÁVŠTĚVNÍKY', 'FOR VISITORS').'</li> -->
    <!-- <li link="/places">'.lang('MÍSTA', 'PLACES').'</li> -->
    <li link="/gallery">'.lang('GALERIE', 'GALLERY').'</li>
    <li class="go_read">'.lang('ČÍTÁRNA', 'READING ROOM').'</li>
    */

    if ($def['jsounovinky'] > 0) {
    echo '
    <li link="/news">'.lang('NOVINKY', 'NEWS').'</li>';
    }
    if ($def['ofestu'] != 0) {
    echo '
    <li link="/about">'.lang('O FESTIVALU', 'FESTIVAL').'</li>';
    }
    if ($def['partneri'] != 0) {
    echo '
    <li link="/partners">'.lang('PARTNEŘI', 'PARTNERS').'</li>';
    }
    if ($def['kontakt'] != 0) {
    echo '
    <li link="/contact">'.lang('KONTAKTY', 'CONTACTS').'</li>';
    }
    echo '
    <li link="/archive">'.lang('ARCHIV', 'ARCHIVE'); if ($def['actrok'] != $rok) {echo ' → '.$rok;} echo '</li>
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
