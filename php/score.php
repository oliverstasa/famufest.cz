<?php

  include './sql_open.php';

  $sql = 'SELECT name, cas, pal, hits FROM score WHERE addr = "'.$_POST['addr'].'" ORDER BY hits DESC, pal, cas LIMIT 7';
  $ress = mysqli_query($conn, $sql);
  if (mysqli_num_rows($ress) > 0) {
    $rank = 1;
    echo '
    <tr><td colspan="5">LEADERBOARD [TOP 7] OF '.$_POST['addr'].'</td></tr>
    <tr><td></td></tr>
    <tr><td>RANK</td><td>NAME</td><td title="ACCURACY = TARGETS ON SCREEN / SHOTS FIRED">ACC.</td><td>TIME</td></tr>';
    while ($row = mysqli_fetch_assoc($ress)) {
      switch ($rank) {
        case 1: $rankr = '1ST'; break;
        case 2: $rankr = '2ND'; break;
        case 3: $rankr = '3RD'; break;
        case 4: $rankr = '4TH'; break;
        case 5: $rankr = '5TH'; break;
        case 6: $rankr = '6TH'; break;
        case 7: $rankr = '7TH'; break;
      }
      echo '
      <tr><td>'.$rankr.'</td><td>'.$row['name'].'</td><td title="ACCURACY = TARGETS ON SCREEN / SHOTS FIRED">'.$row['hits'].'/'.$row['pal'].'</td><td>'.($row['cas']/1000).'s</td></tr>';
      $rank++;
    }
  } else {
    echo '<tr><td><span>NO SCORES HERE</span></td></tr>';
  }

  include './sql_close.php';

?>
