<?php
session_start();

include '../fce.php';

// homepage
////////////////////////////////////////////////////////////////////////////////

    include '../sql_open.php';

    $sql = 'SELECT thumb, video FROM rok WHERE rok = "'.$_SESSION['rok'].'" LIMIT 1';
    $ress = mysqli_query($conn, $sql);

    if (mysqli_num_rows($ress) > 0) {

      $vysledek = mysqli_fetch_assoc($ress);
      if ($vysledek['video'] != '' && $vysledek['thumb'] != '') {

          $result = '
          <video class="center" autoplay muted loop poster="/data/up/'.$vysledek['thumb'].'">
            <source src="/data/up/'.$vysledek['video'].'"></source>
          </video>

          <style>
            #homepage {background: url(\'/data/up/'.$vysledek['thumb'].'\') center center; background-size: cover;}

            @media screen and (max-aspect-ratio: 3/2) {
              #homepage {background: url(\'/data/up/'.$vysledek['thumb'].'\') center center; background-size: cover;}
            }
          </style>
          ';

      } else if ($vysledek['thumb'] != '') {

        $result = '
        <style>
          #homepage {background: url(\'/data/up/'.$vysledek['thumb'].'\') center center; background-size: cover;}

          @media screen and (max-aspect-ratio: 3/2) {
            #homepage {background: url(\'/data/up/'.$vysledek['thumb'].'\') center center; background-size: cover;}
          }
        </style>
        ';

      } else {

          $result = '
          <style>
            #homepage {background: url(\'/data/FAMUFEST_OG.jpg\') center center; background-size: cover;}
          </style>
          ';

      }

    } else {

        $result = '
        <style>
          #homepage {background: url(\'/data/FAMUFEST_OG.jpg\') center center; background-size: cover;}
        </style>
        ';

    }

    include '../sql_close.php';

    echo '
    <div id="homepage" class="fadeout">
    '.$result.'
    </div>
    ';

?>
