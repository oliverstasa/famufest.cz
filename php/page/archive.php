<?php
session_start();

include '../fce.php';
include '../sql_open.php';

echo '
<div class="link_list">
<h1>'.lang('Přístupné ročníky', 'Available years').'</h1>
</div>
<br>
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

include '../sql_close.php';
include '../document/lz.php';

?>
