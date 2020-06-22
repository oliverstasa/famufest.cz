<?php
session_start();

include './sql_open.php';

switch ($_GET['action']) {

  // SQL CTENI Z DB
  //////////////////////////////////////////////////////////////////////////////
  case 'checkname':

    $name = strtoupper($_POST['name']);
    if ($name == 'FAMUFEST') {
      echo 'taken';
    } else {
      if ($name != 'JOE LUCKER') {
        $sql = 'SELECT
                (SELECT COUNT(*) FROM chat WHERE UPPER(name) = "'.$name.'" OR UPPER(name) = "(s)'.$name.'(/s)") AS used,
                (SELECT COUNT(*) FROM chat_names WHERE UPPER(name) = "'.$name.'" OR UPPER(name) = "(s)'.$name.'(/s)") AS locked';
        $dotaz = mysqli_query($conn, $sql);
        if (mysqli_num_rows($dotaz) > 0) {
          $data = mysqli_fetch_array($dotaz);
          if ($data['used']+$data['locked'] > 0) {
            echo 'taken';
          }
        }
      }
    }

  break;

  // SQL CTENI Z DB
  //////////////////////////////////////////////////////////////////////////////
  case 'show':

    // $time_ago = date('Y-m-d H:i:00', time() - 6*3600); // 21600 = 6 hodin
    $now = date('Y-m-d H:i:s', time()); // timestamp now

    if ($_POST['lastid'] == 0) {

      //$sql = 'SELECT id, name, msg FROM chat WHERE entered > "'.$time_ago.'"';
      $sql = 'SELECT id, name, msg FROM chat WHERE entered > (SELECT cas_od FROM kino WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'")';

    } else {

      $sql = 'SELECT id, name, msg FROM chat WHERE id > "'.$_POST['lastid'].'" AND entered > (SELECT cas_od FROM kino WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'")';

    }

    $dotaz = mysqli_query($conn, $sql);
    if (mysqli_num_rows($dotaz) > 0) {

      $ress = '';
      while ($data = mysqli_fetch_array($dotaz)) {

      $text = $data['msg'];
        $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $text = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $text);

      $jmeno = $data['name'];
      $jmeno = str_replace("Joe Lucker", '<span class="j">Joe Lucker</span>', $jmeno);
      $jmeno = str_replace(array('(s)', "(/s)"), array('<span class="s">', '</span>'), $jmeno);

      $ress = $ress.'<div class="msg" num="'.$data['id'].'"><strong>'.$jmeno.':</strong>'.$text.'</div>
      ';

      // IF (CAS KONCE - 10min) {UPOZORNIT ZE SE ZAVRE CHAT ZA CHVILI}

      }

      echo $ress;

    }

  break;

  // SQL ZADAVANI
  //////////////////////////////////////////////////////////////////////////////
  case 'add':

      if (isset($_POST['name']) && isset($_POST['msg'])) {

        $jmeno = $_POST['name'];
        $text = $_POST['msg'];

        $text = str_replace(array("/carka/", "/uvozovka/", "/uvozovka2/", "/dvojtecka/", "/strednik/", "/srdce/"), array(",", '\"', "'", ":", ";", "❤"), $text);
        $text = strip_tags($text);
        $text = str_replace(array('("', "('", '")', "')", "<", ">"), array('(', '(', ')', ')', '&lt;', '&gt;'), $text);

        $jmeno = str_replace(array("/carka/", "/uvozovka/", "/uvozovka2/", "/dvojtecka/", "/strednik/", "'", '"', "<", ">"), array(",", '\"', "'", ":", ";", "", "", '&lt;', '&gt;'), $jmeno);
        $jmeno = strip_tags($jmeno);

        if ($text != "") {

          $sql = 'INSERT INTO chat(name, msg) VALUES ("'.$jmeno.'", "'.$text.'")';

        if (mysqli_query($conn, $sql)) {

          echo 'ok';

        } else {

          echo 'error|'.mysqli_error($conn);

        }

        } else {

          echo 'empty';

        }

      } else {

        echo 'wrong';

      }

  break;

  // CHYBA
  //////////////////////////////////////////////////////////////////////////////
  default:

    echo 'wrong';

  break;

}

include './sql_close.php';

?>
