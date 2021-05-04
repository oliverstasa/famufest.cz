<?php

    if (isset($uri)) {

      $url = explode("/", $uri);

      switch ($url[1]) {
        case 'programme':

          if (isset($url[2]) && isset($url[3])) {

            switch($url[2]) {
              case 'event':
                $og_sql = 'SELECT thumb FROM event WHERE link = "'.$url[3].'"';
              break;
            }

          } else {
            $og_sql = 'SELECT og FROM rok WHERE active = 1';
          }

        break;
        case 'films':

          if (isset($url[2]) && isset($url[3])) {

            switch($url[2]) {
              case 'film':
                $og_sql = 'SELECT thumb FROM event WHERE link = "'.$url[3].'"';
              break;
            }

          } else {
            $og_sql = 'SELECT og FROM rok WHERE active = 1';
          }

        break;
        case 'news':

          if (isset($url[2])) {

              $og_sql = 'SELECT thumb FROM news WHERE link = "'.$url[2].'"';

          } else {

              $og_sql = 'SELECT og FROM rok WHERE active = 1';

          }

        break;
        default:

          $og_sql = 'SELECT og FROM rok WHERE active = 1';

        break;
      }

      if (isset($og_sql)) {

        include './php/sql_open.php';

        $og_ress = mysqli_query($conn, $og_sql);
        if (mysqli_num_rows($og_ress) > 0) {

          $og_vysledek = mysqli_fetch_assoc($og_ress);
          echo '<meta property="og:image" content="https://famufest.cz/data/up/'.$og_vysledek['og'].'">';

        }

        include './php/sql_close.php';

        
      } else {

          //$url = explode("/", $uri);
          echo '<meta property="og:image" content="https://famufest.cz/data/FF37_OG.jpg">';

      }

    }
?>
