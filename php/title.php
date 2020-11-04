<?php
isset($uri)?'':session_start();

  $t = 'FAMUFEST';

  if (isset($_GET['t']) || isset($uri)) {

  $prefix = isset($uri)?'./php':'.';

  if (!isset($uri)) {
    include $prefix.'/fce.php';
  }

    $adr = isset($_GET['t'])?$_GET['t']:substr($uri, 1);
    $tier = explode("/", $adr);
    if (sizeof($tier) > 0) {

      switch($tier[0]) {
        default: case 'home': break;
        case 'live':
          $t .= ' — '.lang('ŽIVĚ', 'LIVE');
        break;
        case 'programme':
          $t .= ' — '.lang('PROGRAM', 'PROGRAMME');
          if (isset($tier[1]) && isset($tier[2])) {
            switch ($tier[1]) {
              case 'day':
                if ($tier[2] == 'all') {
                  // $t .= ' — '.lang('CELÝ', 'FULL');
                } else {
                  $day = explode("-", $tier[2]);
                  $t .= ' — '.($day[2]*1).'. '.($day[1]*1);
                }
              break;
              case 'block':
                $t_sql = 'SELECT nazev, nazev_en FROM blok WHERE link = "'.$tier[2].'"';
              break;
              case 'event':
                $t_sql = 'SELECT nazev, nazev_en FROM event WHERE link = "'.$tier[2].'"';
              break;
            }
          }
        break;
        case 'films':
          $t .= ' — '.lang('FILMY', 'FILMS');
          if (isset($tier[1]) && isset($tier[2])) {
            switch ($tier[1]) {
              case 'film':
                $t_sql = 'SELECT nazev, nazev_en FROM event WHERE link = "'.$tier[2].'"';
              break;
              case 'category':
                if ($tier[2] == 'doc-exp-above-15') {
                  $t .= ' — '.lang('DOKUMENT/EXPERIMENT ›15\'', 'DOCUMENT/EXPERIMENTAL ›15\'');
                } else if ($tier[2] == 'doc-exp-under-15') {
                  $t .= ' — '.lang('DOKUMENT/EXPERIMENT ‹15\'', 'DOCUMENT/EXPERIMENTAL ‹15\'');
                } else {
                  $t_sql = 'SELECT nazev, nazev_en FROM kategorie WHERE link = "'.$tier[2].'"';
                }
              break;
            }
          }
        break;
        case 'news':
          $t .= ' — '.lang('NOVINKY', 'NEWS');
          if (isset($tier[1])) {
            $t_sql = 'SELECT nazev, nazev_en FROM news WHERE link = "'.$tier[1].'"';
          }
        break;
        case 'visitors':
          $t .= ' — '.lang('PRO NÁVŠTĚVNÍKY', 'VISITORS');
        break;
        case 'gallery':
          $t .= ' — '.lang('GALERIE', 'GALLERY');
        break;
        case 'about':
          $t .= ' — '.lang('O FESTIVALU', 'ABOUT FESTIVAL');
        break;
        case 'about':
          $t .= ' — '.lang('MÍSTA', 'PLACES');
        break;
        case 'partners':
          $t .= ' — '.lang('PARTNEŘI', 'PARTNERS');
        break;
        case 'contact':
          $t .= ' — '.lang('KONTAKT', 'CONTACT');
        break;
        case 'archive':
          $t .= ' — '.lang('ARCHIV', 'ARCHIVE');
        break;
      }

      if (isset($t_sql)) {

        include $prefix.'/sql_open.php';

        $t_ress = mysqli_query($conn, $t_sql);
        if (mysqli_num_rows($t_ress) > 0) {

          $t_vysledek = mysqli_fetch_assoc($t_ress);
          $t .= ' — '.lang($t_vysledek['nazev'], $t_vysledek['nazev_en']);

        }

        include $prefix.'/sql_close.php';

      }

    }
  }

  echo $t;

?>
