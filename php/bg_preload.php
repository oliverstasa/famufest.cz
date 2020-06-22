<?php

// VRACI POUZE URL OBRAZKU PRO PRELOAD DO JS => DELIMITER |
////////////////////////////////////////////////////////////////////////////////

// 'https://m.media-amazon.com/images/M/MV5BMDliOGVlMDUtZmRhMS00N2QxLWI5ODMtNWExZWI0OGExZmRlXkEyXkFqcGdeQXVyNjc3OTE4Nzk@._V1_.jpg'

if (isset($_GET['db']) && isset($_GET['link'])) {

  include './sql_open.php';

  $sql = 'SELECT thumb FROM '.$_GET['db'].' WHERE link = "'.$_GET['link'].'"';
  $thumb = mysqli_query($conn, $sql);

  if (mysqli_num_rows($thumb) > 0) {

    $tb = mysqli_fetch_assoc($thumb);
    $urls = array('/data/up/'.$tb['thumb']);

  }

  include './sql_close.php';

} else {

  $urls = '';
  // $urls = array('https://3.bp.blogspot.com/-iRl2Z5QOckI/WNyCMmx2TcI/AAAAAAAAA3E/VEnbh-63o8007eV5QGA-Vl9yAMLnoQDkQCK4B/s1600/maxresdefault-1.jpg');

}

$return = false;

foreach ($urls as &$url) {
  switch (pathinfo($url, PATHINFO_EXTENSION)) {
    case 'jpg':
      $delimiter = $return?'|':'';
      $return .= $delimiter.$url;
    break;
  }
}

echo $return;

?>
