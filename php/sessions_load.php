<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// GLOBAL lang
////////////////////////////////////////////////////////////////////////////////

  if (!isset($_SESSION['lang'])) {

    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else {
      $lang = 'en';
    }

      switch ($lang) {
        case "cs":
          $_SESSION['lang'] = 'cs';
        break;
        default: case "en":
          $_SESSION['lang'] = 'en';
        break;
      }

  }

// GLOBAL SESSIONS
////////////////////////////////////////////////////////////////////////////////

    if (file_exists('./php/sql_open.php')) {
      include './php/sql_open.php';
    } else {
      include './sql_open.php';
    }

      $time_enter = date('Y-m-d H:i:00', time()); // timestamp now
      $time_close = date('Y-m-d H:i:59', time()); // timestamp now:59
      $now = date('Y-m-d', time());
      $vcera = date('Y-m-d', strtotime("-1 days")); // včera
      $sql = 'SELECT (SELECT rok FROM rok WHERE active = 1) AS rok,
                     (SELECT typ_webu FROM settings WHERE cas_od < "'.$time_enter.'" AND cas_do > "'.$time_close.'" LIMIT 1) AS typ_webu,
                     (SELECT COUNT(*) FROM kino WHERE cas_od < "'.$time_enter.'" AND cas_do > "'.$time_close.'") AS kino,
                     (SELECT COUNT(*)
                             FROM program
                             WHERE (typ = "blok" AND datum = "'.$vcera.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$vcera.'")) > 0)
                                OR (typ = "event" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$vcera.'")) > 0)
                                OR (typ = "blok" AND online = "2" AND datum = "'.$now.'" AND (SELECT COUNT(*) FROM event WHERE embed <> "" AND id_blok IN (SELECT id_event FROM program WHERE typ = "blok" AND datum = "'.$now.'")) > 0)
                                OR (typ = "event" AND online = "2" AND (SELECT COUNT(*) FROM event WHERE typ = "film" AND embed <> "" AND event.id IN (SELECT id_event FROM program WHERE typ = "event" AND datum = "'.$now.'")) > 0)) AS filmoteka';
      $d_sql = mysqli_query($conn, $sql);
      $datka = mysqli_fetch_assoc($d_sql);

      $_SESSION['rok'] = $datka['rok']; // date('Y');

      // SET SESSIONS
          if ($datka['kino'] > 0) {
            $_SESSION['kino'] = 1;
          } else {
            $_SESSION['kino'] = 0;
          }

          if ($datka['filmoteka'] > 0 && $datka['typ_webu'] == 'filmoteka') {
            $_SESSION['filmoteka'] = 1;
          } else {
            $_SESSION['filmoteka'] = 0;
          }

    if (file_exists('./php/sql_close.php')) {
      include './php/sql_close.php';
    } else {
      include './sql_close.php';
    }


// DAYTIME
////////////////////////////////////////////////////////////////////////////////

    $ted = time();
    $sun = date_sun_info ($ted, '50.07', '14.41'); // LONG, LAT => for praha
    $vychod = $sun['sunrise'];
    $zapad = $sun['sunset'];

    if ($ted > $vychod && $ted < $zapad) {
      $_SESSION['daytime'] = 'day';
    } else {
      $_SESSION['daytime'] = 'night';
    }

// IF TESTING
////////////////////////////////////////////////////////////////////////////////

if (isset($_GET['tester'])) {

    $vars = explode("|", $_GET['tester']);
    if ($vars[0] != $_SESSION['daytime'] || $vars[1] != $_SESSION['kino'] || $vars[2] != $_SESSION['filmoteka']) {
      echo 'reload';
    }

}

?>
