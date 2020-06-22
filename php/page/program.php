<?php
session_start();

include '../sql_open.php';
include '../fce.php';

echo '<div class="link_list">';

  echo '
  <div class="link'; if ($_GET['p'] == 'all') {echo ' selected';} echo '" link="/programme/day/all">'.lang('CELÝ PROGRAM', 'FULL PROGRAMME').'</div>';


  $dny = mysqli_query($conn, 'SELECT distinct(datum) FROM program WHERE rok = '.$_SESSION['rok']);
  if (mysqli_num_rows($dny) > 0) {

      while ($datum = mysqli_fetch_assoc($dny)) {

        $link = '/programme/day/'.$datum['datum'];

        $day = datumtoday($datum['datum']);
            $date = explode('-', $datum['datum']);
            $day_date = ($date[2]*1).'. '.($date[1]*1);

        echo '
        <div class="link'; if ($_GET['p'] == $datum['datum']) {echo ' selected';} echo '" link="'.$link.'">'.$day.' '.$day_date.'.</div>';

      }

      /*
      echo '
      <div class="link vstupenky">'.lang('VSTUPENKY', 'TICKETS').'</div>';
      */

  } else {

    echo '<h1>TENTO ROK SE NIC NEDĚJE</h1>';

  }

echo '
</div>
<div class="content"></div>
';

include '../sql_close.php';

?>
