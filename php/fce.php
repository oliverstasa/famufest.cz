<?php

// LANG - vraci vysledek podle session jazyka
////////////////////////////////////////////////////////////////////////////////
  function lang($a, $b) {
    switch ($_SESSION['lang']) {
      default: case 'en': if($b) {return $b;} else {return $a;} break;
      case 'cs': if ($a) {return $a;} else {return $b;} break;
    }
  }

// GET IP
////////////////////////////////////////////////////////////////////////////////
function country() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);

    $ipdata = @json_decode($output);
    return $ipdata->geoplugin_countryCode;


}

// DATUM
////////////////////////////////////////////////////////////////////////////////
function datumtoday($datum) {
  $day = date('D', strtotime($datum));

  if ($_SESSION['lang'] == 'cs') {
    switch ($day) {
      case 'Mon': $day = 'Po'; break;
      case 'Tue': $day = 'Út'; break;
      case 'Wed': $day = 'St'; break;
      case 'Thu': $day = 'Čt'; break;
      case 'Fri': $day = 'Pá'; break;
      case 'Sat': $day = 'So'; break;
      case 'Sun': $day = 'Ne'; break;
    }
  }

  return $day;
}

// CAS vraci datum takle 5/2020 (MM/YYYY)
////////////////////////////////////////////////////////////////////////////////
function cas($timestamp) {

  $time_arr = explode(" ", $timestamp);
  $date = explode("-", $time_arr[0]);
  $datum = ($date[1]*1).'/'.$date[0];

  return $datum;

}

// CAS vraci datum takle 20. 5. 2020 (DD. MM. YYYY)
////////////////////////////////////////////////////////////////////////////////
function datumko($timestamp) {

  $time_arr = explode(" ", $timestamp);
  $date = explode("-", $time_arr[0]);
  $datum = ($date[2]*1).'. '.($date[1]*1).'. '.$date[0];

  return $datum;

}

// TIMESTAMP
////////////////////////////////////////////////////////////////////////////////
function tmstmp($timestamp) {

  if ($timestamp != '' && $timestamp != 'NULL') {
    $time_arr = explode(" ", $timestamp);
    $date = explode("-", $time_arr[0]);
    $datum = ($date[2]*1).'. '.($date[1]*1).'. '.$date[0];
    $casr = explode(':', $time_arr[1]);
    $cas = ($casr[0]).'.'.($casr[1]).'';

    return $datum.' - '.$cas;
  } else {
    return '-';
  }

}

?>
