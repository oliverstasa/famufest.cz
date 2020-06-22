<script src="/js/fce.admin.js" type="text/javascript"></script>
<link href="/css/admin.css" rel="stylesheet">
<?php
session_start();

if (isset($_SESSION['lord']) == true) {

  include '../sql_open.php';

  if (isset($_GET['pg'])) {

    $pg = explode("/", $_GET['pg']);
    $str = isset($pg[2])?$pg[2]:false;

    echo '
    <div id="admin_menu">
    <form id="rok_form">
    <select id="rok_select" from="/admin'.(isset($oblast)?'/'.$oblast:'').(isset($akce)?'/'.$akce:'').(isset($id)?'/'.$id:'').'">
    ';

      include '../sql_open.php';
      $sql = 'SELECT rok FROM rok ORDER BY rok';
      $ress = mysqli_query($conn, $sql);
      if (mysqli_num_rows($ress) > 0) {
        while ($row = mysqli_fetch_assoc($ress)) {
          echo '
          <option value="'.$row['rok'].'"'; if ($row['rok'] == $_SESSION['rok']) {echo ' selected';} echo '>'.$row['rok'].'</option>'; // date('Y')
        }
        echo '
        <option value="add">+</option>
        ';
      } else {
        echo '
        <option value="0">-</option>';
      }

    echo '
    </select>
    </form>
    <div class="link'; if ($str == 'news') {echo ' selected';} echo '" link="/admin/news/show" id="m_novinky">NOVINKY</div>
    <div class="link'; if ($str == 'event') {echo ' selected';} echo '" link="/admin/event/show" id="m_eventy">EVENTY</div>
    <div class="link'; if ($str == 'kategorie') {echo ' selected';} echo '" link="/admin/kategorie/show" id="m_kategorie">KATEGORIE</div>
    <div class="link'; if ($str == 'blok') {echo ' selected';} echo '" link="/admin/blok/show" id="m_bloky">BLOKY</div>
    <div class="link'; if ($str == 'venue') {echo ' selected';} echo '" link="/admin/venue/show" id="m_venues">VENUES</div>
    <div class="link'; if ($str == 'program') {echo ' selected';} echo '" link="/admin/program/show" id="m_program">PROGRAM</div>
    <div class="link'; if ($str == 'kino') {echo ' selected';} echo '" link="/admin/kino/show" id="m_kino">KINO</div>
    <div class="link'; if ($str == 'chat_names') {echo ' selected';} echo '" link="/admin/chat_names/show" id="m_chat">CHAT</div>
    <div class="link'; if ($str == 'settings') {echo ' selected';} echo '" link="/admin/settings/show" id="m_rezim">REŽIM</div>
    <div class="link'; if ($str == 'rok') {echo ' selected';} echo '" link="/admin/rok/show" id="m_rocnik">ROČNÍK</div>
    <div class="link" link="/admin/logout">ODHLÁSIT SE</div>
    </div>
    ';

    echo '
    <div class="content">
    ';

    if ($_GET['pg'] != '/admin') {

      $page = $_GET['pg'];
      include './db.php';

    } else {

        echo '
        <img src="/data/img/lasergirl_pixel.gif" style="max-width: 70vw;">
        <br>
        <h1>Kontakty</h1>
        <strong>Developer a webmaster</strong><br>
        &emsp;Oliver Staša
        &emsp;oliver.stasa@gmail.com<br>
        &emsp;Po—Ne 12.00—20.00&emsp;+420 737 840 602 [SMS]<br><br>
        <strong>Grafická koncepce a design</strong><br>
        &emsp;Filip Kopecký
        &emsp;abc@filipkopecky.com<br>
        &emsp;Po—Pá 8.00—18.00&emsp;+420 737 787 373
        ';

    }

    echo '
    </div>
    ';

  }

  include '../sql_close.php';

} else {

  echo '
  <h1>Přihlášení do dministrace</h1>
  *přihlašovací údaje poskytuje vedení festivalu
  <form id="login_form">
    <br><br>
    <input type="text" name="login" id="login_name" placeholder="login"><br><br>
    <input type="password" name="pass" id="login_pass" placeholder="password"><br><br>
    <input type="submit" name="ok" id="login_submit" value="Přihlásit se">
  </form>
  ';

}

?>
