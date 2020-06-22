<?php
session_start();
include '../fce.php';

echo '
<style>
  body {background: transparent;}
  .fullscreen_thumb {background: url(\'/data/img/galerie.jpg\') center center; background-size: cover;}
</style>
<div class="fullscreen_thumb fadeout"></div>

<script>
    if (bg_preload("https://www.famufest.cz/data/img/galerie.jpg") && $(".fullscreen_thumb").ready()) {
      setTimeout(function(){
        $(".fullscreen_thumb").removeClass("fadeout");
      }, 100);
    }
</script>
<div class="link_list">
  <div class="link go_gallery">→ '.lang('VSTOUPIT DO GALERIE', 'ENTER THE GALLERY').'</div>
  <!--
  <div class="link" link="">'.lang('STÁHNOUT AFFECTION DETOUR [PC]', 'DOWNLOAD AFFECTION DETOUR [PC]').'</div>
  <div class="link" link="">'.lang('STÁHNOUT AFFECTION DETOUR [MAC]', 'DOWNLOAD AFFECTION DETOUR [MAC]').'</div>
  -->
</div>
<div class="content">
<img class="fullthumb lozad" src="/data/img/loading.gif" data-src="/data/img/galerie.jpg">
<h1>Affection Detour</h1>
<div class="pg_normal">
<div class="content" style="padding-top: 0vh;">
<p>
';

echo lang('Katedra fotografie a Centrum audiovizuálních studií FAMU spolu vstupují do volného vztahu a s láskou zvou na výstavu Affection Detour pod záštitou letošního ročníku FAMUFESTU. Letos naprosto odlišně – veškerá díla naleznete na speciálně stvořeném virtuálním ostrově. Vstoupit a prozkoumávat budete moci přes www.famufest.cz po celou dobu konání festivalu.', 'Department of Photography and the Centre for Audiovisual Studies of FAMU are entering a loose relationship, and they invite you with love to the exhibition entitled Affection Detour held under the auspices of this year’s edition of FAMUFEST. Things are completely different this year – you can view all the works in a specially created virtual island. You can enter and explore via www.famufest.cz during the entire term of the festival.');

echo '
</p>
<p>
Love you,<br>
&nbsp;and you<br>
&nbsp;&nbsp;and you<br>
&nbsp;&nbsp;&nbsp;and also you.<br>
<br>
You know?<br>
&nbsp;&nbsp;&nbsp;You all know.
</p>

<p>';

echo lang('Polyamorie jako možnost a způsob vzniku jakéhokoliv vztahu, uskupení a komunikace. Zároveň i výhled rychlého konce. Are you ready? Like really? We hope so.', 'Polyamory as an option and way of establishing any relationship, group and communication. With an outlook for a rapid ending as well. Are you ready? Like really? We hope so.');

echo '
</p>
Katedra fotografie:<br>
– Alexander Rossa<br>
– Ezra Šimek<br>
– Karin Petrič<br>
– Hana Selena Sokolovic<br>
– Oskar Helcel<br>
<br>
Centrum audiovizuálních studií:<br>
– Vendula Guhová<br>
– Ester Grohová<br>
– Anežka Horová<br>
– Adrián Kriška<br>
<br>
Kurátoři: Karolína Schön (KF) a Alex Sihelská (CAS)<br>
VR editors: Jakub Krejčí, Alena Kols, Veronika Švecová<br>
Produkce: Julie Ondračková, Alžběta Málková
';

echo '
</div>
</div>
</div>';

include '../document/lz.php';

?>
