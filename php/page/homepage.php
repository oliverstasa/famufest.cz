<?php

include '../fce.php';

    // sql for URL
    //$url1 = 'https://3.bp.blogspot.com/-iRl2Z5QOckI/WNyCMmx2TcI/AAAAAAAAA3E/VEnbh-63o8007eV5QGA-Vl9yAMLnoQDkQCK4B/s1600/maxresdefault-1.jpg';
    $urls = array('/data/img/bg1.jpg', '/data/img/bg2.jpg');

    $video = '/data/FF36.mp4';
    $poster = '/data/ff36.jpg';

    $file_type = pathinfo($video, PATHINFO_EXTENSION);

// homepage
////////////////////////////////////////////////////////////////////////////////

    switch ($file_type) {
      // JPG
      //////////////////
      case 'jpg':
        $result = '
        <style>
          #homepage {background: url(\''.$urls[0].'\') center center; background-size: cover;}
        </style>
        ';
      break;
      // MP4
      //////////////////
      case 'mp4':
        $result = '
        <video class="center" autoplay muted loop poster="'.$poster.'">
          <source src="'.$video.'"></source>
        </video>

        <style>
          #homepage {background: url(\''.$poster.'\') center center; background-size: cover;}

          @media screen and (max-aspect-ratio: 3/2) {
            #homepage {background: url(\''.$urls[rand(0,1)].'\') center center; background-size: cover;}
          }
        </style>
        ';
      break;
      // BRUTAL ERROR
      //////////////////
      default:
        $result = 'brutally non-solvable extra-hard error, try washing your teeth';
      break;
    }

    echo '
    <div id="homepage" class="fadeout">
    '.$result.'
    </div>
    ';

?>
