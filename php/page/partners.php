<?php
session_start();

include '../fce.php';
include '../sql_open.php';

$loading = '/data/img/loading.gif';
$dir = '/data/partneri/';
$prevkat = false;
$pr = 1;

  $sql = 'SELECT
          id, id_kat, nazev, odkaz, logo,
          (SELECT nazev FROM partneri_kat WHERE id = partneri.id_kat) AS kat_nazev,
          (SELECT nazev FROM partneri_kat WHERE id = partneri.id_kat) AS kat_nazev_en
          FROM partneri
          WHERE rok = "'.$_SESSION['rok'].'"
          ORDER BY id_kat DESC, id';
  $part = mysqli_query($conn, $sql);

  if (mysqli_num_rows($part) > 0) {

    echo '<div id="partners" class="content" style="padding-top: 0;">';

    while ($partner = mysqli_fetch_assoc($part)) {

      if ($prevkat != $partner['id_kat']) {
        echo '<h1>'.lang($partner['kat_nazev'], $partner['kat_nazev_en']).'</h1>';
        if ($pr > 1) {echo '</div>';}
        echo '<div class="partners">';
      }

      echo '<a href="'.$partner['odkaz'].'" target="_blank">';

      if ($partner['logo']) {
        echo '<img class="lozad parneros '.$_SESSION['daytime'].'" src="'.$loading.'" data-src="'.$dir.$partner['logo'].'">';
      } else {
        echo $partner['nazev'];
      }

      echo '</a>';

      $prevkat = $partner['id_kat'];
      $pr++;

    }

    echo '</div>';

  } else {

    echo '<h1>'.lang('ŽÁDNÍ PARTNEŘI', 'NO PARTNERS').'</h1>';

  }

include '../sql_close.php';
include '../document/lz.php';

/*
</div>

  <h1>'.lang('Pořadatel festivalu', 'Festival organizer').'</h1>
  <div class="partners">
    <a href="https://www.famu.cz/cs/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/FAMU_BILA.png', '/CERNA/FAMU_CERNA_01.png').'"></a>
  </div>

  <h1>'.lang('Festival vznikl za podpory', 'The festival was created with the support').'</h1>
  <div class="partners">
    <a href="https://www.mkcr.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/MINSTERSTVO_KULTURY-POZTIV-BILA-RGB.png', '/CERNA/MINSTERSTVO_KULTURY-POZTIV-CERNA-RGB.png').'"></a>
    <a href="http://www.msmt.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/MSMT-POZITIV-BILA-RGB.png', '/CERNA/MSMT-POZITIV-CERNA-RGB.png').'"></a>
    <a href="https://fondkinematografie.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/SFK_NEGATIV-RGB.png', '/CERNA/SFK_POZITIV-RGB.png').'"></a>
    <a href="http://www.praha.eu/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/CERNA/PRAHA_POZITIV-RGB.png', '/BILA/PRAHA_NEGATIV-RGB.png').'"></a>
  </div>

  <h1>'.lang('Hlavní partneři', 'Main partners').'</h1>
  <div class="partners">
    <a href="http://mall.tv/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/MALL-BILA.png', '/CERNA/MALL-CERNA.png').'"></a>
  </div>

  <h1>'.lang('Partneři festivalu', 'Main partners').'</h1>
  <div class="partners">
    <a href="https://www.biofilms.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/bio_small_bw_nottransparent_negative.png', '/CERNA/bio_small_bw_nottransparent_positive.png').'"></a>
    <a href="https://champagneria.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/chamagneria.png', '/CERNA/chamagneria.png').'"></a>
  </div>

  <h1>'.lang('Hlavní mediální partneři', 'Main media partners').'</h1>
  <div class="partners">
    <a href="https://www.novinky.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/NOVINKY-BILA.png', '/CERNA/NOVINKY-CENRA.png').'"></a>
    <a href="https://pravo.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/PRAVO-BILA.png', '/CERNA/PRAVO-CERNA.png').'"></a>
    <a href="https://wave.rozhlas.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/WAVE-BILA.png', '/CERNA/WAVE-CERNA.png').'"></a>
  </div>

  <h1>'.lang('Mediální partneři', 'Media partners').'</h1>
  <div class="partners">
    <a href="https://goout.net/cs/praha/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/GoOut.png', '/CERNA/GoOut.png').'"></a>
    <a href="https://www.radio1.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/RADIO1-BILA.png', '/CERNA/RADIO1-CERNA.png').'"></a>
    <a href="http://25fps.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/25FPS-BILA.png', '/CERNA/25FPS-CERNA.png').'"></a>
    <a href="http://czechmag.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/CZECHMAG-bila.png', '/CERNA/CZECHMAG-cerna.png').'"></a>
    <a href="http://naformat.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/FORMAT-BILA.png', '/CERNA/FORMAT-CERNA.png').'"></a>
    <a href="http://artikl.org/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/ARTIKL-BILA.png', '/CERNA/ARTIKL-CERNA.png').'"></a>
    <a href="https://www.bigboard.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/BIGBOARD-BILA.png', '/CERNA/BIGBOARD-CERNA.png').'"></a>
    <a href="http://cinepur.cz/" target="_blank">CINEPUR</a>
  </div>

  <h1>'.lang('Partneři cen', 'Prices partners').'</h1>
  <div class="partners">
    <a href="https://www.panavision.com/worldwide/panavision-prague" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/PANAVISION-BILA-01.png', '/CERNA/PANAVISION-CERNA-01.png').'"></a>
    <a href="https://www.arrirental.com/en" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/ARRI-BILA.png', '/CERNA/ARRI-CERNA.png').'"></a>
    <a href="https://www.soundsquare.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/S2-BILA.png', '/CERNA/S2-CERNA.png').'"></a>
    <a href="https://www.magiclab.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/MAGIC-LAB-BILA.png', '/CERNA/MAGIC-LAB-CERNA.png').'"></a>
    <a href="https://www.biofilms.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/bio_small_bw_nottransparent_negative.png', '/CERNA/bio_small_bw_nottransparent_positive.png').'"></a>
    <a href="https://www.vantagefilm.com/en/rentals" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/VANTAGE-BILA.png', '/CERNA/VANTAGE-BLACK.png').'"></a>
    <a href="http://www.radiosrental.cz/cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/RADIOSRENTAL-BILA.png', '/CERNA/RADIOSRENTAL-CERNA.png').'"></a>
    <a href="http://losrentalos.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/LOS_BILA.png', '/CERNA/LOS_CERNA.png').'"></a>
  </div>

  <h1>'.lang('Partnerské festivaly', 'Partner festivals').'</h1>
  <div class="partners">
    <a href="https://www.kviff.com/cs/uvod" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/kviff-white.png', '/CERNA/kviff-black.png').'"></a>
    <a href="https://www.ji-hlava.cz/" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/JIHLAVA-BILA-01.png', '/CERNA/JIHLAVA-CERNA.png').'"></a>
    <a href="http://www.anifilm.cz/cs/" target="_blank">ANIFILM</a>
  </div>

  <h1>'.lang('Partneři znělky', 'Partners of the jingle').'</h1>
  <div class="partners">
    <a href="https://www.panavision.com/worldwide/panavision-prague" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/PANAVISION-BILA-01.png', '/CERNA/PANAVISION-CERNA-01.png').'"></a>
    <a href="https://www.upp.cz" target="_blank"><img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/UPP-BILA-01.png', '/CERNA/UPP-cerna.png').'"></a>
    <img class="lozad" src="'.$loading.'" data-src="'.$dir.daytime('/BILA/versus-white.png', '/CERNA/versus-black.png').'">
    <a href="#">Tricksfx</a>
  </div>

  <!--
  <h1>'.lang('Partnerské podniky festivalu', 'Partner venues of the festival').'</h1>
  <div class="partners">
  </div>
  -->
</div>
*/
?>
