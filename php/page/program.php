<?php
session_start();

include '../sql_open.php';
include '../fce.php';

echo '<div class="link_list">';

  echo '
  <div class="link'; if ($_GET['p'] == 'all') {echo ' selected';} echo '" link="/programme/day/all">'.lang('CELÝ PROGRAM', 'FULL PROGRAMME').'</div>';
  
  $now = date('Y-m-d H:i:s', time());

  $dny = mysqli_query($conn, 'SELECT distinct(datum), (SELECT COUNT(*) FROM donate WHERE rok = "'.$_SESSION['rok'].'" AND cas_od < "'.$now.'" AND cas_do > "'.$now.'") AS donate FROM program WHERE rok = '.$_SESSION['rok']);
  if (mysqli_num_rows($dny) > 0) {

      while ($datum = mysqli_fetch_assoc($dny)) {

        $link = '/programme/day/'.$datum['datum'];

        $day = datumtoday($datum['datum']);
            $date = explode('-', $datum['datum']);
            $day_date = ($date[2]*1).'. '.($date[1]*1);

        echo '
        <div class="link'; if ($_GET['p'] == $datum['datum']) {echo ' selected';} echo '" link="'.$link.'">'.$day.' '.$day_date.'.</div>';

        $donate = $datum['donate'];

      }

      if ($donate) {
        
        $don = mysqli_query($conn, 'SELECT nazev, nazev_en, odkaz FROM donate WHERE rok = "'.$_SESSION['rok'].'" AND cas_od < "'.$now.'" AND cas_do > "'.$now.'"');
        $donat = mysqli_fetch_assoc($don);

        echo '
        <div class="link vstupenky" odkaz="'.$donat['odkaz'].'">'.lang($donat['nazev'], $donat['nazev_en']).'</div>';
      
      }

  } else {

    echo '<h1>TENTO ROK SE NIC NEDĚJE</h1>';

  }

echo '
</div>
<div class="content"></div>
';

include '../sql_close.php';

?>
