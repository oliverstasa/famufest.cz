<?php
session_start();

include '../fce.php';
include '../sql_open.php';

echo '
<h1>'.lang('Přepnout na ročník', 'Switch to year').'</h1>
<br>
<div class="content">
';

    $sql = 'SELECT
            rok, thumb, termin
            FROM rok
            WHERE publikovano = 1
            ORDER BY rok DESC';
    $roky = mysqli_query($conn, $sql);

      while ($rok = mysqli_fetch_assoc($roky)) {

        //echo '<div class="link'; if ($rok['rok'] == $_SESSION['rok']) {echo ' selected';} echo '">'.$rok['rok'].'</div>';

        if ($rok['thumb']) {
          $thumb = '/data/up/s/'.$rok['thumb'];
        } else {
          $thumb = '/data/img/default_'.$_SESSION['daytime'].'.jpg';
        }

        echo '
        <div class="rocnik film';

        if ($rok['rok'] == $_SESSION['rok']) {echo ' thisyear';}

        echo '" link="/archive/'.$rok['rok'].'">
          <img class="thumb lozad" src="/data/img/loading.gif" data-src="'.$thumb.'">
          <div class="popis">
            <div class="nazev">'.$rok['rok'].'</div>
            <div class="delka">'.$rok['termin'].'</div>
          </div>
        </div>';
      }


echo '
</div>
<br>
<h1>'.lang('Archiv', 'Archive').'</h1>
<div class="link_list">
';

      $stare_roky = mysqli_query($conn, 'SELECT * FROM archiv_rocniky ORDER BY rok DESC');
      while ($rok = mysqli_fetch_assoc($stare_roky)) {

          echo '<div class="link" rok="'.$rok['rok'].'">'.$rok['rok'].($rok['tema']?' — '.$rok['tema']:'').'</div>
          ';

      }

echo '
</div>
<div class="pg_normal">
<br>
';

      $stare_roky = mysqli_query($conn, 'SELECT * FROM archiv_rocniky');
      while ($rok = mysqli_fetch_assoc($stare_roky)) {

        echo '
        <div class="archivni_rocnik" id="ra'.$rok['rok'].'">
        <h1>'.$rok['rok'].($rok['tema']?' — '.$rok['tema']:'').'</h1>
        <div class="content">
        '.($rok['misto']?'<h2>'.$rok['misto'].'</h2>':'').'
        '.($rok['popis']?'<p>'.$rok['popis'].'</p>':'').'
        '.($rok['organizace']?'<br><h2>Organizátoři</h2>'.$rok['organizace']:'').'
        '.($rok['porota']?'<br><br><h2>Porota</h2>'.$rok['porota']:'').'
        ';

        $vitezove = mysqli_query($conn, 'SELECT * FROM archiv_ceny WHERE rok = "'.$rok['rok'].'" ORDER BY RAND()');
        if (mysqli_num_rows($vitezove) > 0) {
          echo '
          <br><br>
          <h2>Vítězové</h2>
          <table>
          ';
          while ($vitez = mysqli_fetch_assoc($vitezove)) {
            echo '<tr><td>'.$vitez['cena'].'</td><td>'.$vitez['titulek'].'</td></tr>
            ';
          }
          echo '
          </table>
          ';
        }

        echo '
        </div>
        </div>';

      }

echo '</div>';

include '../sql_close.php';
include '../document/lz.php';

?>
