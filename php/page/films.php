<?php
session_start();

include '../fce.php';
include '../sql_open.php';

echo '
<div class="link_list">
';

    $aramis = 0;
    $now = date('Y-m-d', time());
    $vcera = date('Y-m-d', strtotime("-1 days")); // včera
    $sql = 'SELECT DISTINCT id_kat,
                   (SELECT nazev FROM kategorie WHERE id = event.id_kat) AS nazev,
                   (SELECT nazev_en FROM kategorie WHERE id = event.id_kat) AS nazev_en,
                   (SELECT link FROM kategorie WHERE id = event.id_kat) AS link,
                   (SELECT COUNT(*) FROM event WHERE typ = "event" AND embed <> "" AND rok = "'.$_SESSION['rok'].'") AS zaznamy,
                   (SELECT COUNT(*) FROM event WHERE typ = "event" AND aramis = 1 AND rok = "'.$_SESSION['rok'].'") AS aramis,
                   (SELECT COUNT(*)
                           FROM program
                           WHERE (typ = "blok" AND online = "1" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$vcera.'")) > 0)
                              OR (typ = "event" AND online = "1" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$vcera.'")) > 0)
                              OR (typ = "blok" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$now.'")) > 0)
                              OR (typ = "event" AND online = "2" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$now.'")) > 0)) AS k_prehrani
            FROM event WHERE rok = "'.$_SESSION['rok'].'"
            ORDER BY id_kat';
    $kat = mysqli_query($conn, $sql);
    if (mysqli_num_rows($kat) > 0) {
        //$dx1 = $dx2 = true;
        $tested = false;
        while ($cat = mysqli_fetch_assoc($kat)) {

          if ($tested === false) {

            $tested = true;
            if ($cat['k_prehrani'] > 0 && $_SESSION['filmoteka'] > 0) {
              echo '
              <div class="link" link="/films/category/play-now"><div class="toplay"></div>'.lang('K PŘEHRÁNÍ', 'PLAY NOW').'</div>';
            }

            if ($cat['zaznamy'] > 0) {
              $zaznamy = 1;
            } else {
              $zaznamy = 0;
            }

            echo '
            <div class="link" link="/films">'.lang('VŠECHNY FILMY', 'ALL FILMS').'</div>
            ';

          }

          if ($cat['id_kat']) {

            /*
            if ($cat['id_kat'] == 4 || $cat['id_kat'] == 6) {
              if ($dx1) {
                $adr = 'doc-exp-within-15';
                $nazev = 'DOKUMENT/EXPERIMENT DO 15\'';
                $nazev_en = 'DOCUMENT/EXPERIMENTAL WITHIN 15\'';
              }
              $dx1 = false;
            } else if ($cat['id_kat'] == 5 || $cat['id_kat'] == 7) {
              if ($dx2) {
                $adr = 'doc-exp-above-15';
                $nazev = 'DOKUMENT/EXPERIMENT NAD 15\'';
                $nazev_en = 'DOCUMENT/EXPERIMENTAL ABOVE 15\'';
              }
              $dx2 = false;
            } else {
              $adr = $cat['link']; $nazev = $cat['nazev']; $nazev_en = $cat['nazev_en'];
            }
            */

            $adr = $cat['link'];
            $nazev = lang($cat['nazev'], $cat['nazev_en']);
            $link = '/films/category/'.$adr;

            echo '
            <div class="link" link="'.$link.'">'.$nazev.'</div>';

            $aramis = $cat['aramis'];

          }

        }

    }

    /*
    $kat = array(
              array('link' => 'feature-within-15', 'nazev' => 'HRANÝ FILM DO 15\'', 'nazev_en' => 'FEATURE FILM WITHIN 15\''),
              array('link' => 'feature-above-15', 'nazev' => 'HRANÝ FILM NAD 15\'', 'nazev_en' => 'FEATURE FILM ABOVE 15\''),
              array('link' => 'animated-film', 'nazev' => 'ANIMOVANÝ FILM', 'nazev_en' => 'ANIMATED FILM'),
              array('link' => 'doc-exp-within-15', 'nazev' => 'DOKUMENT/EXPERIMENT DO 15\'', 'nazev_en' => 'DOCUMENT/EXPERIMENTAL WITHIN 15\''),
              array('link' => 'doc-exp-above-15', 'nazev' => 'DOKUMENT/EXPERIMENT NAD 15\'', 'nazev_en' => 'DOCUMENT/EXPERIMENTAL ABOVE 15\''),
              array('link' => 'aramis-price', 'nazev' => 'ARAMISOVA CENA', 'nazev_en' => 'ARAMIS PRICE'),
              array('link' => 'famo-pisek', 'nazev' => 'FAMO PÍSEK', 'nazev_en' => 'FAMO PÍSEK'),
              array('link' => 'utb-zlin', 'nazev' => 'UTB ZLÍN', 'nazev_en' => 'UTB ZLÍN'),
              array('link' => 'retrospective', 'nazev' => 'OHLÉDNUTÍ', 'nazev_en' => 'RETROSPECTIVE')
          );

    for ($i = 0; $i < sizeof($kat); $i++) {
      $link = '/films/category/'.$kat[$i]['link'];
      echo '
      <div class="link" link="'.$link.'">'.lang($kat[$i]['nazev'], $kat[$i]['nazev_en']).'</div>';
    }
    */

include '../sql_close.php';

if ($aramis > 0) {

  echo '
  <div class="link" link="/films/category/aramis-price">'.lang('ARAMISOVA CENA', 'ARAMIS PRICE').'</div>';

}

if ($zaznamy == 1) {
  echo '
  <div class="link" link="/films/category/event-recordings"><div class="toplay"></div>'.lang('UDÁLOSTI', 'EVENTS').'</div>';
}

echo '
</div>
<div class="content"></div>
';

?>
