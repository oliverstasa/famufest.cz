<?php
session_start();

if ($_SESSION['lord'] === true) {

  include '../sql_open.php';

  // ROZDELENI POST PROMENNYCH DO POLE
  //////////////////////////////////////////////////////////////////////////////
  $list = explode(",", $_POST['list']);
  foreach ($list as &$val) {
    $row = explode(":", $val);
      $name = $row[0];
      /*
      $value = str_replace('"', '', $row[1]);
      $value = str_replace("/dvojtecka/", ":", $value);
      $value = str_replace("/strednik/", ";", $value);
      $value = str_replace("/carka/", ",", $value);
      $value = str_replace("/uvozovka/", '\"', $value);
      $value = str_replace("/uvozovka2/", "'", $value);
      */
      $value = str_replace('"', '', $row[1]);
      $value = str_replace(array("/carka/", "/uvozovka/", "/uvozovka2/", "/dvojtecka/", "/strednik/"), array(",", '\"', "'", ":", ";"), $value);
      $params[$name] = $value;
  }

  // TEST NESHOD
  //////////////////////////////////////////////////////////////////////////////
  $errors = array();
  $sql_tester = array();
  $control = '';
  if ($params['table'] != 'program' && $params['table'] != 'partneri') {

    if ($_POST['akce'] == 'edit') {$control = ' AND id <> '.$_POST['id'];}
    foreach ($params as $key => $val) {
      if ($key == 'link') {
        if (((substr($val, 0, 4) == $_SESSION['rok'] && sizeof(explode("-", $val)) >= 2) || $params['table'] == 'event' || $params['table'] == 'kategorie') && strpos($val, " ") === false) {
          array_push($sql_tester, '(SELECT COUNT(*) FROM '.$params['table'].' WHERE '.$key.' = "'.$val.'"'.$control.') AS '.$key);
        } else {
          array_push($errors, $key);
        }

  // VYJIMKY
  //////////////////////////////////////////////////////////////////////////////
      } else if ($key != 'table' && $key != 'id_blok' && $key != 'id_kat' && $key !='delka' && $key != 'stream_link' && $key != 'geoblok' &&
                 $key != 'typ' && $key != 'active' && $key != 'aramis' && $key != 'color' && $key != 'typ_webu' && $key != 'online' &&
                 $key != 'publikovano' && $key != 'popis' && $key != 'popis_en' && $key != 'cas_od' && $key != 'cas_do') {
        if ($val) {
          array_push($sql_tester, '(SELECT COUNT(*) FROM '.$params['table'].' WHERE '.$key.' = "'.$val.'"'.$control.') AS '.$key);
        }
      }
    }

    if (sizeof($sql_tester) > 0) {
      $sql = 'SELECT '.implode(', ', $sql_tester).' FROM '.$params['table'];
      $dotaz_test = mysqli_query($conn, $sql);
      if (mysqli_num_rows($dotaz_test) > 0) {

          $row = mysqli_fetch_assoc($dotaz_test);
          foreach ($row as $key => $val) {
              if ($val) {
                array_push($errors, $key);
              }
          }

      }
    }

  }

  // POKUD JSOU NESHODY
  //////////////////////////////////////////////////////////////////////////////
  if (sizeof($errors)) {

    echo 'duplicate|'.implode(",",$errors);

  // POKUD NEJSOU NESHODY => PRACE S DATABAZI
  } else {

    switch($_POST['akce']) {
      case 'edit':

        $insert = array();
        foreach ($params as $key => $val) {
          if ($key != 'table') {
            array_push($insert, $key.' = "'.$val.'"');
          }
        }

        $sql = 'UPDATE '.$params['table'].' SET '.implode(", ", $insert).' WHERE id = '.$_POST['id'];

      break;
      case 'add':

        $insert_names = array();
        $insert_values = array();
        foreach ($params as $key => $val) {
          if ($key != 'table') {
            array_push($insert_names, $key);
            array_push($insert_values, '"'.$val.'"');
          }
        }

        if ($params['table'] == 'rok' || $params['table'] == 'settings' || $params['table'] == 'kategorie' || $params['table'] == 'kino' || $params['table'] == 'partneri_kat') {
          $sql = 'INSERT INTO '.$params['table'].'('.implode(", ", $insert_names).') VALUES ('.implode(", ", $insert_values).')';
        } else {
          $sql = 'INSERT INTO '.$params['table'].'('.implode(", ", $insert_names).', rok) VALUES ('.implode(", ", $insert_values).', '.$_SESSION['rok'].')';
        }

      break;
    }


  // SQL ZADAVANI
  //////////////////////////////////////////////////////////////////////////////
    if (mysqli_query($conn, $sql)) {
      echo 'done';
    } else {
      echo 'error|'.mysqli_error($conn);
    }

  }

  include '../sql_close.php';

}

?>
