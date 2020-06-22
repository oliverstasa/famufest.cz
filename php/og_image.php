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

          }

        break;
        case 'films':

          if (isset($url[2]) && isset($url[3])) {

            switch($url[2]) {
              case 'film':
                $og_sql = 'SELECT thumb FROM event WHERE link = "'.$url[3].'"';
              break;
            }

          }

        break;
        case 'news':

          if (isset($url[2])) {

              $og_sql = 'SELECT thumb FROM news WHERE link = "'.$url[2].'"';

          }

        break;
      }

      if (isset($og_sql)) {

        include './php/sql_open.php';

        $og_ress = mysqli_query($conn, $og_sql);
        if (mysqli_num_rows($og_ress) > 0) {

          $og_vysledek = mysqli_fetch_assoc($og_ress);
          echo '<meta property="og:image" content="https://famufest.cz/data/up/'.$og_vysledek['thumb'].'">';

        }

        include './php/sql_close.php';

      } else {

          $url = explode("/", $uri);
          if ($url[1] == 'gallery') {
            echo '<meta property="og:image" content="https://famufest.cz/data/img/galerie.jpg">';
          } else {
            echo '<meta property="og:image" content="https://famufest.cz/data/FAMUFEST_OG_1.png">';
          }

      }

    }
?>
