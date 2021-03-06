<?php
include '../fce.php';
include '../fce.admin.php';

if (isset($page) && $_SESSION['lord'] === true) {

$pg = explode("/", $page);
$oblast = isset($pg[2])?$pg[2]:false;
$akce = isset($pg[3])?$pg[3]:false;
$id = isset($pg[4])?$pg[4]:false;

  if ($oblast == 'settings' && $akce == 'show') {
    echo '
      <script>
        setTimeout(function(){
          reload_page("force");
        }, 100);
      </script>
    ';
  }

echo '
<div class="link'; if ($akce == 'show') {echo ' selected';} echo '" link="/admin/'.$oblast.'/show">👁 ZOBRAZIT</div>
<div class="link'; if ($akce == 'add') {echo ' selected';} echo '" link="/admin/'.$oblast.'/add">♘ PŘIDAT</div>
';

helfer($oblast, true);


echo '
<div class="content" style="min-width: 60vh;">
';

/*
switch ($oblast){

  case 'settings': echo 'vysvětlivky'; break;

}
*/

  switch ($akce) {

// SHOW / DEFAULTNI AKCE
////////////////////////////////////////////////////////////////////////////////
    default: case 'show':

      echo "
      <script>
        if ($('#trumbowyg-icons').length) {
          $('#trumbowyg-icons').remove();
        }
      </script>
      ";

      switch ($oblast) {
        case 'rok':
          $names = array(array('rok', 'ROK'), array('termin', 'TERMÍN'), array('og', 'OG (.jpg)'), array('thumb', 'BG/THUMB (.jpg)'), array('video', 'VIDEO (.mp4)'), array('publikovano', 'VEŘEJNÉ'), array('active', 'STAV'));
          $sql = 'SELECT id, rok, publikovano, active, termin, og, thumb, video FROM rok';
        break;
        case 'program':
          $names = array(array('event', 'EVENT'), array('venue', 'VENUE'), array('datum', 'DATUM'), array('zacatek', 'ZAČÁTEK'), array('konec', 'KONEC'), array('online', 'ONLINE'));
          $sql = 'SELECT id, typ, id_event, (SELECT nazev FROM venue WHERE id = program.id_venue) AS venue, datum, zacatek, konec, online FROM program WHERE rok = '.$_SESSION['rok'];
        break;
        case 'event':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'), array('typ', 'TYP'), array('blok', 'BLOK'), array('id_katr', 'KATEGORIE'), array('embed', 'VIDEO'), array('thumb', 'THUMB (.jpg)'));
          $sql = 'SELECT rok, id, (SELECT zkr FROM blok WHERE id = event.id_blok) AS blok, id_kat AS id_katr, typ, link, nazev, nazev_en, popis, popis_en, thumb, aramis, embed, geoblok FROM event WHERE rok = '.$_SESSION['rok'];
        break;
        case 'blok':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'), array('zkr', 'ZKRATKA'), array('podcast', 'PODCAST'), array('playlist', 'PLAYLIST'), array('reklama', 'REKLAMA (.mp4)'));
          $sql = 'SELECT id, zkr, nazev, nazev_en, podcast, playlist, reklama FROM blok WHERE rok = '.$_SESSION['rok'];
        break;
        case 'venue':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'), array('color', 'BARVA'));
          $sql = 'SELECT id, nazev, nazev_en, color FROM venue WHERE rok = '.$_SESSION['rok'];
        break;
        case 'news':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'),  array('publikovano', 'PUBLIKOVÁNO'), array('cas_od', 'OD'), array('thumb', 'THUMB (.jpg)'));
          $sql = 'SELECT id, nazev, nazev_en, publikovano, cas_od, thumb FROM news WHERE rok = '.$_SESSION['rok'];
        break;
        case 'mag':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'),  array('publikovano', 'PUBLIKOVÁNO'), array('cas_od', 'OD'), array('thumb', 'THUMB (.jpg)'));
          $sql = 'SELECT id, nazev, nazev_en, publikovano, cas_od, thumb FROM mag WHERE rok = '.$_SESSION['rok'];
        break;
        case 'settings':
          $names = array(array('typ_webu', 'REŽIM'), array('cas_od', 'SPUSTÍ SE'), array('cas_do', 'UKONČÍ SE'));
          $sql = 'SELECT id, typ_webu, cas_od, cas_do FROM settings';
        break;
        case 'kino':
          $names = array(array('nazev', 'NÁZEV [CZ]'), array('nazev_en', 'NÁZEV [EN]'), array('stream_link', 'ODKAZ NA STREAM'), array('cas_spusteni', 'ZAPNE SE'), array('cas_od', 'ZPŘÍSTUPNÍ SE'), array('cas_do', 'UZAVŘE SE'));
          $sql = 'SELECT id, nazev, nazev_en, stream_link, cas_spusteni, cas_od, cas_do FROM kino';
        break;
        case 'chat_names':
          $names = array(array('name', 'JMÉNO'), array('hash', 'ODKAZ'));
          $sql = 'SELECT id, name, hash FROM chat_names';
        break;
        case 'kategorie':
          $names = array(array('nazev', 'NÁZEV'), array('nazev_en', 'NÁZEV [EN]'));
          $sql = 'SELECT id, nazev, nazev_en FROM kategorie';
        break;
        case 'pages':
          $names = array(array('nazev', 'NÁZEV V MENU [CZ]'), array('nazev_en', '[EN]'), array('cas_od', 'OD'), array('cas_do', 'DO'), array('obsah', 'OBSAH [CZ]'), array('obsah_en', '[EN]'), array('publikovano', 'PUBLIKOVÁNO'), array('thumb', 'THUMB (.jpg)'));
          $sql = 'SELECT id, nazev, nazev_en, cas_od, cas_do, obsah, obsah_en, publikovano, thumb FROM pages';
        break;
        case 'partneri':
          $names = array(array('logo', 'LOGO (celobílé transparentní .png)'), array('nazev', 'PARTNER'), array('odkaz', 'ODKAZ'), array('kategorie', 'KATEGORIE'));
          $sql = 'SELECT id, nazev, odkaz, logo, (SELECT nazev FROM partneri_kat WHERE id = partneri.id_kat) AS kategorie FROM partneri WHERE rok = '.$_SESSION['rok'];
        break;
        case 'partneri_kat':
          $names = array(array('nazev', 'NÁZEV'), array('nazev_en', 'NÁZEV [EN]'));
          $sql = 'SELECT id, nazev, nazev_en FROM partneri_kat';
        break;
        case 'contacts':
          $names = array(array('page', 'STRÁNKA [CZ]'), array('page_en', 'STRÁNKA [EN]'), array('timestamp', 'ZMĚNĚNO'));
          $sql = 'SELECT id, page, page_en, timestamp FROM contacts WHERE rok = '.$_SESSION['rok'];
        break;
        case 'about':
          $names = array(array('page', 'STRÁNKA [CZ]'), array('page_en', 'STRÁNKA [EN]'), array('timestamp', 'ZMĚNĚNO'));
          $sql = 'SELECT id, page, page_en, timestamp FROM about WHERE rok = '.$_SESSION['rok'];
        break;
        case 'lista':
          $names = array(array('lista', 'TEXT [CZ]'), array('lista_en', 'TEXT [EN]'), array('cas_od', 'OD'), array('cas_do', 'DO'));
          $sql = 'SELECT id, lista, lista_en, cas_od, cas_do FROM lista';
        break;
        case 'donate':
          $names = array(array('nazev', 'TEXT [CZ]'), array('nazev_en', 'TEXT [EN]'), array('odkaz', 'ODKAZ'), array('cas_od', 'OD'), array('cas_do', 'DO'));
          $sql = 'SELECT id, nazev, nazev_en, odkaz, cas_od, cas_do FROM donate WHERE rok = '.$_SESSION['rok'];
        break;
        case 'entrypage':
          $names = array(array('page', 'STRÁNKA [CZ]'), array('page_en', 'STRÁNKA [EN]'), array('cas_od', 'ZAPNE SE'), array('cas_do', 'UZAVŘE SE'));
          $sql = 'SELECT id, page, page_en, cas_od, cas_do FROM entrypage WHERE rok = '.$_SESSION['rok'];
        break;
      }

      $ress = mysqli_query($conn, $sql.' ORDER BY id DESC');
      if (mysqli_num_rows($ress) > 0) {

          $i = 0;
          echo '
          <table class="seznam">
          ';

            while ($row = mysqli_fetch_assoc($ress)) {

                if ($i == 0) {
                  echo '
                  <thead><tr>';

                    for ($b = 0; $b < sizeof($names); $b++) {
                      echo '<th>'.$names[$b][1].'</th>
                      ';
                    }
                    echo '<th>NÁSTROJE</th>'; // 🛠

                  echo '
                  </tr></thead>
                  <tbody>';
                }

                echo '<tr>';

                foreach ($names as &$val) { // $key=>$val
                  $key = $val[0];
                  switch ($key) {
                    /*
                    case 'id_blok':
                      $res = '';
                    break;
                    */
                    case 'thumb': case 'og': case 'logo':
                      //$res = $row[$key]?'✔':'⨯';
                      if ($key == 'logo') {
                        $rlurl = '/data/partneri/';
                        $rlcls = ' ispng '.$_SESSION['daytime'];
                      } else {
                        $rlurl = '/data/up/s/';
                      }
                      $thumb = $row[$key]?$rlurl.$row[$key]:'/data/img/upload.jpg';
                      $res = '
                      <form class="thumb_form" fk_id="'.$row['id'].'" table="'.$oblast.'" key="'.$key.'">
                        <img src="'.$thumb.'" class="thumb'.$rlcls.'">
                        <input type="file" accept="'; if ($key == 'logo') {$res .= 'image/png';} else {$res .= 'image/jpeg, image/jpg';} $res .= '" class="thumb_input" name="thumb_input">
                      </form>';
                    break;
                    case 'active':
                      $res = $row[$key]?'[AKTIVNÍ]':'<div class="changeyear" to="'.$row['id'].'">[AKTIVOVAT]</div>';
                    break;
                    case 'color':
                      $res = '<div class="color" style="background: #'.$row[$key].'"></div>';
                    break;
                    /*
                    case 'publikovano':
                      $res = $row[$key]?'✔':'⨯';
                    break;
                    */
                    case 'id_katr':

                      $usek = substr($row[$key], 1, -1);
                      $kats = explode('||', $usek);
                      $resss = array();

                      if (count($kats)) {
                        for ($ku = 0; $ku < count($kats); $ku++) {    
                          $katr = mysqli_query($conn, 'SELECT nazev FROM kategorie WHERE id = '.$kats[$ku]);
                          $nazev = mysqli_fetch_assoc($katr);
                          array_push($resss, $nazev['nazev']);
                        }
                        $res = implode('<br>', $resss);
                      } else {
                        $katr = mysqli_query($conn, 'SELECT nazev FROM kategorie WHERE id = '.$kats[$ku]);
                        $nazev = mysqli_fetch_assoc($katr);
                        $res = $nazev['nazev'];
                      }

                      if ($row['aramis'] == 1) {
                        $res = $res.' + ARAMIS';
                      }
                    break;
                    case 'event':
                      $jmeno = mysqli_query($conn, 'SELECT nazev FROM '.$row['typ'].' WHERE id = '.$row['id_event']);
                      $nazev = mysqli_fetch_assoc($jmeno);
                      $res = $nazev['nazev'];
                    break;
                    case 'nazev_en':
                      $res = $row[$key]?'<span title="'.$row[$key].'">✔</span>':'⨯';
                    break;
                    case 'typ':
                      $res = strtoupper($row[$key]);
                    break;
                    case 'stream_link': case 'page': case 'page_en': case 'odkaz': case 'termin': case 'obsah': case 'obsah_en': case 'publikovano': case 'playlist': case 'podcast': case 'lista_en':
                      $res = $row[$key]?'✔':'⨯';
                    break;
                    case 'video': case 'reklama':
                      $thumb = $row[$key]?'<video class="thumb thumb_video" autoplay muted loop><source src="/data/up/'.$row[$key].'"></video>':'<img src="/data/img/upload.jpg" class="thumb thumb_video">';
                      $res = '
                      <form class="video_form" fk_id="'.$row['id'].'" table="'.$oblast.'" key="'.$key.'">
                        '.$thumb.'
                        <input type="file" accept="video/mp4" class="video_input" name="video_input">
                      </form>';
                    break;
                    case 'embed':
                      $res = $row[$key]?'✔':'⨯';
                      if ($row['geoblok'] == 1) {
                        $res = $res.' (ČR)';
                      }
                    break;
                    case 'nazev':
                      if (strlen($row[$key]) > 30) {
                        $res = substr($row[$key], 0, 30).'...';
                      } else {
                        $res = $row[$key];
                      }
                    break;
                    case 'hash':
                      $res = '<div class="copyme" copytext="https://www.famufest.cz/live/chat/'.$row[$key].'">ZKOPÍROVAT ODKAZ</div>';
                    break;
                    case 'online':
                      switch($row[$key]) {
                        case 0: default: $res = "OFFLINE"; break;
                        case 1: $res = "ONLINE PO 24H"; break;
                        case 2: $res = "ONLINE IHNED"; break;
                      }
                    break;
                    case 'cas_od': case 'cas_do': case 'zacatek': case 'konec': case 'cas_spusteni': case 'timestamp':
                      $res = tmstmp($row[$key]);
                    break;
                    case 'datum':
                      $res = datumko($row[$key]);
                    break;
                    default:
                      $res = $row[$key];
                    break;
                  }
                  echo '<td>'.$res.'</td>
                  ';
                }

                  echo '
                  <td>
                    ';
                    if ($oblast == 'news' || $oblast == 'mag' || $oblast == 'venue' || $oblast == 'partneri' || $oblast == 'partneri_kat' || $oblast == 'about' || $oblast == 'contact') {

                      $switcher = mysqli_query($conn, 'SELECT id, (SELECT id FROM '.$oblast.' WHERE id > '.$row['id'].' AND rok = '.$_SESSION['rok'].' ORDER BY id LIMIT 1) AS id_prev, (SELECT id FROM '.$oblast.' WHERE id < '.$row['id'].' AND rok = '.$_SESSION['rok'].' ORDER BY id DESC LIMIT 1) AS id_next FROM '.$oblast.' LIMIT 1');
                      $id = mysqli_fetch_assoc($switcher);

                      if ($id['id_next']) {
                        echo '
                        <div class="link" link="/admin/'.$oblast.'/switch/'.$row['id'].'-'.$id['id_next'].'">⇓</div>';
                      }
                      if ($id['id_prev']) {
                        echo '
                        <div class="link" link="/admin/'.$oblast.'/switch/'.$id['id_prev'].'-'.$row['id'].'">⇑</div>';
                      }

                    }

                    echo '<div class="link" link="/admin/'.$oblast.'/edit/'.$row['id'].'">✎</div>';
                    if (/* ($oblast == 'kategorie' && $row['id'] > 7) || */ $oblast != 'kategorie') {
                    echo '
                    <div class="delete" link="/admin/'.$oblast.'/delete/'.$row['id'].'">🗑</div>';
                    } /*
                    else {
                    echo '-';
                    }
                    */

                    echo '
                  </td>
                  ';

                echo '</tr>';

                $i++;

            }

          echo '
          </tbody>
          </table>
          ';

      } else {

          echo '
          <h1>ŽÁDNÝ ZÁZNAM</h1>
          ';

      }

    break;

// ADD and EDIT
////////////////////////////////////////////////////////////////////////////////
    case 'add': case 'edit':

      $editor_descrip = '<br>FUNKCE EDITORU:<br>viz. [?] PRÁCE S EDITOREM<br>';

      switch ($oblast) {
        case 'rok':
          $vals = array(
                    array('sql' => 'publikovano', 'name' => 'PUBLIKOVANO', 'desc' => 'povolení zobrazovat se', 'type' => 'checkbox'),
                    array('sql' => 'rok', 'name' => 'ROČNÍK', 'desc' => '2020, 2021, ...', 'type' => 'txt'),
                    array('sql' => 'termin', 'name' => 'TERMÍN', 'desc' => 'zobrazí se v pravém dolním rohu:<br><i>XX</i>th FILM FESTIVAL FAMUFEST <i>XX</i>.—<i>XX</i>.<i>XX</i>.<br>36th FILM FESTIVAL FAMUFEST 20.—25.5.', 'type' => 'txt')
                    // array('sql' => 'active', 'name' => 'AKTIVNÍ', 'desc' => 'pokud zakliknuto aktivní, tento ročník se defaultně zobrazuje', 'type' => 'checkbox')
                  );
        break;
        case 'program':
          $vals = array(
                    array('sql' => 'typ', 'name' => 'TYP', 'desc' => '', 'type' => 'select', 'values' => 'blok,event'),
                    array('sql' => 'online', 'name' => 'ONLINE', 'desc' => '', 'type' => 'select'),
                    array('sql' => 'id_event1', 'name' => 'EVENT', 'desc' => 'film nebo událost', 'type' => 'select', 'fkey' => 'event WHERE rok = "'.$_SESSION['rok'].'"'),
                    array('sql' => 'id_event2', 'name' => 'BLOK', 'desc' => '', 'type' => 'select', 'fkey' => 'blok WHERE rok = "'.$_SESSION['rok'].'"'),
                    array('sql' => 'id_venue', 'name' => 'VENUE', 'desc' => '', 'type' => 'select', 'fkey' => 'venue WHERE rok = "'.$_SESSION['rok'].'"'),
                    array('sql' => 'datum', 'name' => 'DATUM', 'desc' => '*datum, pod který den událost spadá', 'type' => 'date'),
                    array('sql' => 'zacatek', 'name' => 'ZAČÁTEK', 'desc' => '*čas začátku může být následující den<br>např. pokud "afterparty" začíná po půlnoci v 01:00, tak:<br>když DATUM je 2020-05-20 => ZAČÁTEK je 2020-05-21 01:00:00', 'type' => 'timestamp'),
                    array('sql' => 'konec', 'name' => 'KONEC', 'desc' => '', 'type' => 'timestamp')
                  );
        break;
        case 'event':
          $vals = array(
                    array('sql' => 'typ', 'name' => 'TYP', 'desc' => '', 'type' => 'select', 'values' => 'film,event'),
                    array('sql' => 'id_kat', 'name' => 'KATEGORIE', 'desc' => '', 'type' => 'checkbox', 'fkey' => 'kategorie ORDER BY id DESC'),
                    array('sql' => 'id_blok', 'name' => 'BLOK', 'desc' => '', 'type' => 'select', 'fkey' => 'blok WHERE rok = "'.$_SESSION['rok'].'"'),
                    array('sql' => 'soutez', 'name' => 'REŽIM', 'desc' => '-/soutěž/nesoutěž', 'type' => 'select'),
                    array('sql' => 'aramis', 'name' => 'ARAMISOVA CENA', 'desc' => '', 'type' => 'checkbox'),
                    array('sql' => 'delka', 'name' => 'DÉLKA', 'desc' => 'pouze číslo v minutách, zaokrouhleno', 'type' => 'txt'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => 'typ:FILM => /films/ODKAZ<br>typ:EVENT => /events/ODKAZ', 'type' => 'txt'),
                    array('sql' => 'embed', 'name' => 'VIMEO/YOUTUBE LINK', 'desc' => 've tvaru "https://vimeo.com/376880888" apod.<br>*pokud je odkaz vyplněn, zobrazuje se ve videotéce<br>celý následující den po uvedení filmu v programu', 'type' => 'txt'),
                    array('sql' => 'geoblok', 'name' => 'GEOBLOK ČR', 'desc' => 'zaškrtnuto => pouze v ČR<br>nezaškrtnuto => worldwide', 'type' => 'checkbox'),
                    array('sql' => 'popis', 'name' => 'OBSAH [CZ]', 'desc' => '<br><div class="entertits">vložit štábovou šablonu</div><br><br><br>*pro automatické dublování pozic mezi CZ<=>EN<br>použij "....." (5× tečka[.]) mezi pozicí a jmény', 'type' => 'txtarea'),
                    array('sql' => 'popis_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'txtarea')
                  );
        break;
        case 'blok':
          $vals = array(
                    array('sql' => 'zkr', 'name' => 'ZKRATKA', 'desc' => 'A1', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => $_SESSION['rok'].'-A1 =><br>/programme/block/'.$_SESSION['rok'].'-A1', 'type' => 'txt'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => 'ANIMOVANÉ FILMY 1', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => 'ANIMATED FILMS 1', 'type' => 'txt'),
                    array('sql' => 'podcast', 'name' => 'SOUNDCLOUD PODCAST', 'desc' => 'odkaz na soundclound<br>tzn. https://soundcloud.com/xyz', 'type' => 'txt'),
                    array('sql' => 'playlist', 'name' => 'VIMEO PLAYLIST', 'desc' => 'odkaz na playlist z vimea<br>tzn. https://vimeo.com/showcase/8293897', 'type' => 'txt')
                  );
        break;
        case 'venue':
          $vals = array(
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => 'KINO PILOTŮ', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => 'EL PILOTA CINÉMA', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => $_SESSION['rok'].'-kinopilotu =><br>/programme/block/'.$_SESSION['rok'].'-kinopilotu', 'type' => 'txt'),
                    array('sql' => 'adresa', 'name' => 'ADRESA', 'desc' => 'Jaguárova 557/1', 'type' => 'txt'),
                    array('sql' => 'color', 'name' => 'BARVA', 'desc' => '', 'type' => 'select')
                );
        break;
        case 'news':
          $vals = array(
                    array('sql' => 'publikovano', 'name' => 'PUBLIKOVANO', 'desc' => 'povolení zobrazovat se', 'type' => 'checkbox'),
                    array('sql' => 'cas_od', 'name' => 'OD KDY', 'desc' => 'zobrazí se od tohoto času', 'type' => 'timestamp'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => 'min. 20 znaků<br>např. VYHLÁŠENÍ VÍTĚZŮ 2020', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => 'min. 20 znaků', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => $_SESSION['rok'].'-WINNERS =><br>/news/'.$_SESSION['rok'].'-WINNERS', 'type' => 'txt'),
                    array('sql' => 'obsah', 'name' => 'OBSAH [CZ]', 'desc' => '', 'type' => 'div'),
                    array('sql' => 'obsah_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'mag':
          $vals = array(
                    array('sql' => 'publikovano', 'name' => 'PUBLIKOVANO', 'desc' => 'povolení zobrazovat se', 'type' => 'checkbox'),
                    array('sql' => 'cas_od', 'name' => 'OD KDY', 'desc' => 'zobrazí se od tohoto času', 'type' => 'timestamp'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => $_SESSION['rok'].'-CLANEK =><br>/mag/'.$_SESSION['rok'].'-CLANEK', 'type' => 'txt'),
                    array('sql' => 'obsah', 'name' => 'OBSAH [CZ]', 'desc' => '', 'type' => 'div'),
                    array('sql' => 'obsah_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'settings':
          $vals = array(
                    array('sql' => 'typ_webu', 'name' => 'REŽIM', 'desc' => 'default => klasické konání festivalu<br>filmotéka => filmy ve videotéce + default', 'type' => 'select'),
                    array('sql' => 'cas_od', 'name' => 'SPUSTÍ SE', 'desc' => '', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'UKONČÍ SE', 'desc' => '', 'type' => 'timestamp')
                );
        break;
        case 'pages':
          $vals = array(
                    array('sql' => 'publikovano', 'name' => 'PUBLIKOVANO', 'desc' => 'povolení zobrazovat se', 'type' => 'checkbox'),
                    array('sql' => 'cas_od', 'name' => 'ZOBRAZÍ SE', 'desc' => '', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'SKRYJE SE', 'desc' => '', 'type' => 'timestamp'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV V MENU [CZ]', 'desc' => 'optimal 5-8 znaků, max 15 znaků', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV V MENU [EN]', 'desc' => 'optimal 5-8 znaků, max 15 znaků', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => $_SESSION['rok'].'-COVID-19 =><br>/p/'.$_SESSION['rok'].'-COVID-19', 'type' => 'txt'),
                    array('sql' => 'obsah', 'name' => 'OBSAH [CZ]', 'desc' => '', 'type' => 'div'),
                    array('sql' => 'obsah_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'kino':
          $vals = array(
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'stream_link', 'name' => 'ODKAZ NA STREAM', 'desc' => '
                    TVAR ODKAZU:<br><strong>⨯ EMBED</strong>: <span style="text-decoration: line-through;">&lt;iframe&gt;</span>, <span style="text-decoration: line-through;">&lt;div&gt;</span>, <span style="text-decoration: line-through;">&lt;script&gt;</span>, <span style="text-decoration: line-through;">&lt;html&gt;</span>, <span style="text-decoration: line-through;">&lt;embed&gt;</span>
                    <br><br><strong>✔ YOUTUBE</strong>:<li>https://www.youtube.com/watch?v=XS088Opj9o0</li><li>https://youtu.be/XS088Opj9o0</li>
                    <strong>✔ FACEBOOK</strong>:<li>https://www.facebook.com/highedition/videos/2661865314036684/</li><li>https://www.facebook.com/watch/?v=275630553121328</li>
                    <strong>✔ VIMEO</strong>:<li>https://vimeo.com/376880888</li>', 'type' => 'txt'),
                    array('sql' => 'cas_spusteni', 'name' => 'ZAPNE SE STREAM', 'desc' => '*čas zveřejnění streamu', 'type' => 'timestamp'),
                    array('sql' => 'cas_od', 'name' => 'ZPŘÍSTUPNÍ SE MÍSTNOST S CHATEM', 'desc' => '*čas oveření KINO místnosti<br>s chatem a odpočtem do začátku streamu', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'UZAVŘE SE', 'desc' => '*čas uzavření kino místnosti<br>*počítejte, že po skončení livestreamu<br>může pokračovat diskuse v chatu<br><br>*toto časové okno zároveň určuje<br>hranice historie zpráv v chatu', 'type' => 'timestamp')
                );
        break;
        case 'chat_names':
          $vals = array(
                    array('sql' => 'name', 'name' => 'JMÉNO', 'desc' => 'dostane speciální barvu v chatu', 'type' => 'txt'),
                    array('sql' => 'hash', 'name' => 'HASH', 'desc' => '', 'type' => 'txt')
                );
        break;
        case 'kategorie':
          $vals = array(
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'link', 'name' => 'ODKAZ', 'desc' => 'ohlednuti =><br>/films/category/ohlednuti<br>*pozor! není závislé na ročníku => platí plošně', 'type' => 'txt')
                );
        break;
        case 'partneri':
          $vals = array(
                    array('sql' => 'id_kat', 'name' => 'KATEGORIE', 'desc' => '', 'type' => 'select', 'fkey' => 'partneri_kat'),
                    array('sql' => 'nazev', 'name' => 'NÁZEV', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'odkaz', 'name' => 'ODKAZ', 'desc' => 'https://www.xy.com', 'type' => 'txt')
                );
        break;
        case 'partneri_kat':
          $vals = array(
                    array('sql' => 'nazev', 'name' => 'NÁZEV [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'NÁZEV [EN]', 'desc' => '', 'type' => 'txt')
                );
        break;
        case 'contacts':
          $vals = array(
                    array('sql' => 'page', 'name' => 'OBSAH [CZ]', 'desc' => $editor_descrip, 'type' => 'div'),
                    array('sql' => 'page_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'about':
          $vals = array(
                    array('sql' => 'page', 'name' => 'OBSAH [CZ]', 'desc' => $editor_descrip, 'type' => 'div'),
                    array('sql' => 'page_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'entrypage':
          $vals = array(
                    array('sql' => 'cas_od', 'name' => 'OTEVŘE SE', 'desc' => '*čas kdy se začně stránka zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'UZAVŘE SE', 'desc' => '*čas kdy se přestane stránka zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'page', 'name' => 'OBSAH [CZ]', 'desc' => $editor_descrip, 'type' => 'div'),
                    array('sql' => 'page_en', 'name' => 'OBSAH [EN]', 'desc' => '', 'type' => 'div')
                );
        break;
        case 'donate':
          $vals = array(
                    array('sql' => 'cas_od', 'name' => 'ZOBRAZÍ SE', 'desc' => '*čas kdy se začně button zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'SKRYJE SE', 'desc' => '*čas kdy se přestane button zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'nazev', 'name' => 'TEXT [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'nazev_en', 'name' => 'TEXT [EN]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'odkaz', 'name' => 'ODKAZ', 'desc' => '', 'type' => 'txt')
                  );
        break;
        case 'lista':
          $vals = array(
                    array('sql' => 'cas_od', 'name' => 'ZOBRAZÍ SE', 'desc' => '*čas kdy se začně lišta zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'cas_do', 'name' => 'SKRYJE SE', 'desc' => '*čas kdy se přestane lišta zobrazovat', 'type' => 'timestamp'),
                    array('sql' => 'lista', 'name' => 'TEXT [CZ]', 'desc' => '', 'type' => 'txt'),
                    array('sql' => 'lista_en', 'name' => 'TEXT [EN]', 'desc' => '', 'type' => 'txt')                    
                  );
        break;
      }

      if (isset($id) && $akce == 'edit') {
        $edit_line = array();
        foreach ($vals as &$val) {
          $valplk = $val['sql'];
          if ($val['sql'] == 'id_event1' || $val['sql'] == 'id_event2') {
            $valplk = 'id_event';
          }
          array_push($edit_line, $valplk);
        }
        $edit = mysqli_query($conn, 'SELECT '.implode(',', $edit_line).' FROM '.$oblast.' WHERE id = '.$id);
        if (mysqli_num_rows($edit) > 0) {

            $row = mysqli_fetch_assoc($edit);
            $editing = array();
            foreach ($row as $key => $value) {
              $editing[$key] = $value;
            }

        }
      }

      if ($akce == 'edit' && $oblast == 'program') {

        echo '
        <script>
          setTimeout(function(){
            $(\'option[selected="selected"]\').each(function(){ $(this).prop(\'selected\', true);});
            program_type();
          }, 100);
        </script>
        ';

      } else if ($oblast == 'news' || $oblast == 'mag' || $oblast == 'partneri' || $oblast == 'about' || $oblast == 'contacts' || $oblast == 'entrypage' || $oblast == 'pages') {

        $editor_count = 1;

        echo '
          <script src="/js/editor_json/dist/editor.js"></script>
          <script src="/js/editor_json/dist/image.js"></script>
          <script src="/js/editor_json/dist/table.js"></script>
          <script src="/js/editor_json/dist/header.js"></script>
        ';

      } else if ($oblast == 'event' && $akce == 'edit') {

        echo '
        <script>
          setTimeout(function(){
            $(\'option[selected="selected"]\').each(function(){ $(this).prop(\'selected\', true);});
            check_event();
          }, 100);
        </script>
        ';

      }

      echo '
      <form id="db_form" autocomplete="off">
      <table class="form">
      ';
      for ($v = 0; $v < sizeof($vals); $v++) {
        //echo $editing[$vals[$v]['sql']];
        echo '
        <tr';
          if (($oblast == 'program' && ($vals[$v]['sql'] == 'id_event1' || $vals[$v]['sql'] == 'id_event2')) || ($oblast == 'event' && $vals[$v]['sql'] == 'id_kat')) {
            echo ' class="hidden"';
          }
        echo '><td>'.$vals[$v]['name'];
          if ($vals[$v]['desc']) { echo '<br><div class="desc">'.$vals[$v]['desc'].'</div>';}
        echo '</td><td>';

        switch ($vals[$v]['type']) {
          // TEXT
          ///////////////
          case 'txt':
            echo '<input autocomplete="off" type="text"';
              if ($vals[$v]['sql'] == 'hash') {echo ' disabled';}
              if (isset($id) && $akce == 'edit') {echo ' value="'.$editing[$vals[$v]['sql']].'"';} else
              if ($vals[$v]['sql'] == 'link' && ($oblast == 'blok' || $oblast == 'news' || $oblast == 'mag' || $oblast == 'venue' || $oblast == 'pages')) {echo ' value="'.$_SESSION['rok'].'-"';}
            echo ' name="'.$vals[$v]['sql'].'">';
          break;
          // TEXTAREA
          ///////////////
          case 'txtarea':
            echo '<textarea autocomplete="off" name="'.$vals[$v]['sql'].'">';
              if (isset($id) && $akce == 'edit') {echo $editing[$vals[$v]['sql']];}
            echo '</textarea>';
          break;
          // DIV = JSON textarea
          ///////////////
          case 'div':

              echo '
              <script>

              var editor_'.$editor_count.' = new EditorJS({

                holder: \'editorjs_'.$editor_count.'\'
                ,

                onChange: () => {
                  previewContent'.$editor_count.'();
                }
                ,

                tools: {

                    header: {
                      class: Header,
                      config: {
                        placeholder: \'Nadpis\',
                        levels: [2], // [2, 3, 4]
                        defaultLevel: 2
                      }
                    }
                ,
                    image: {
                      class: ImageTool,
                      config: {
                        types: \'image/jpg\',
                        endpoints: {
                          byFile: \'/php/admin/imgUploadJSON.php\'
                        }
                      }
                    }
                ,
                    table: {
                      class: Table,
                      inlineToolbar: true,
                      config: {
                        rows: 2,
                        cols: 2
                      }
                    }

                }
                ';

                if (isset($id) && $akce == 'edit' && $editing[$vals[$v]['sql']] != '') {
                echo '
                ,
                data: '.str_replace(array("'", "https:/", "http:/"), array('\"', "https://", "http://"), $editing[$vals[$v]['sql']]);
                }

                echo '

              });

              ';

              if (isset($id) && $akce == 'edit' && $editing[$vals[$v]['sql']] != '') {
                echo '
                setTimeout(function(){
                  previewContent'.$editor_count.'();
                }, 1500);
                ';
              }

              echo '

              </script>
              <div id="editorjs_'.$editor_count.'" class="editorjs" name="'.$vals[$v]['sql'].'"></div><div class="editor_preview'; if ($oblast == 'partneri' || $oblast == 'pages' || $oblast == 'news' || $oblast == 'mag') {echo ' partneri';} echo '" id="editor_preview_'.$editor_count.'"></div><input class="fullthumb" id="editor_json_'.$editor_count.'">
              ';

            $editor_count++;

          break;
          // SELECT
          ///////////////
          case 'select':
            echo '<select autocomplete="off" name="'.$vals[$v]['sql'].'"';
              if ($oblast == 'program' && $vals[$v]['sql'] == 'typ') {echo ' class="programtype"';} else
              if ($oblast == 'event' && $vals[$v]['sql'] == 'typ') {echo ' class="katselect"';} else 
              if ($oblast == 'event' && $vals[$v]['sql'] == 'soutez') {echo ' class="soutezselect"';}
              echo '>';
              if (isset($vals[$v]['values'])) {
                $values = explode(",", $vals[$v]['values']);
                foreach ($values as &$valda) {
                  echo '
                  <option value="'.$valda.'"'; if ($akce == 'edit' && $valda == $editing[$vals[$v]['sql']]) {echo ' selected="selected"';} echo '>'.$valda.'</option>';
                }
              } else if ($vals[$v]['sql'] == 'color') {
                $barvy = array('33FF00', '8FFFCC', 'FF3333', '660066', '9999FF', 'CC9900', '666600', 'FF6600', 'FF66CC', '000099', '6699FF', '00CC66', '663300', 'FF6666', 'CC99FF');
                for ($brva = 0; $brva < sizeof($barvy); $brva++) {
                  echo '
                  <option value="'.$barvy[$brva].'" style="background: #'.$barvy[$brva].'; color: white;"';
                  if ($akce == 'edit' && $barvy[$brva] == $editing[$vals[$v]['sql']]) {echo ' selected="selected"';}
                  echo '>#'.$barvy[$brva].'</option>';
                }
              } else if ($vals[$v]['sql'] == 'typ_webu') {
                $typywebu = array('default', 'filmoteka');
                for ($tw = 0; $tw < sizeof($typywebu); $tw++) {
                  echo '
                  <option value="'.$typywebu[$tw].'"';
                  if ($akce == 'edit' && $typywebu[$tw] == $editing[$vals[$v]['sql']]) {echo ' selected="selected"';}
                  echo '>'.$typywebu[$tw].'</option>';
                }
              } else if ($vals[$v]['sql'] == 'online') {
                  echo '
                  <option value="0"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 0) {echo ' selected="selected"';}
                  echo '>OFFLINE</option>';
                  echo '
                  <option value="1"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 1) {echo ' selected="selected"';}
                  echo '>ONLINE PO 24H</option>';
                  echo '
                  <option value="2"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 2) {echo ' selected="selected"';}
                  echo '>ONLINE IHNED</option>';
              } else if ($vals[$v]['sql'] == 'soutez') {
                  echo '
                  <option value="0"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 0) {echo ' selected="selected"';}
                  echo '>-</option>';
                  echo '
                  <option value="1"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 1) {echo ' selected="selected"';}
                  echo '>SOUTEŽNÍ</option>';
                  echo '
                  <option value="2"';
                  if ($akce == 'edit' && $editing[$vals[$v]['sql']] == 2) {echo ' selected="selected"';}
                  echo '>NESOUTĚŽNÍ</option>';
              } else {
                if ($vals[$v]['sql'] == 'id_event1' || $vals[$v]['sql'] == 'id_event2') {
                  $search_var = 'id_event';
                } else {
                  $search_var = $vals[$v]['sql'];
                }

                $table_test = $vals[$v]['fkey'];
                $addoner = false;

                if (substr($vals[$v]['fkey'], 0, 4) == 'blok') {
                  $addoner = ', zkr';
                }

                $selected = false;
                $f_table = mysqli_query($conn, 'SELECT id, nazev'.$addoner.' FROM '.$vals[$v]['fkey']);
                  while ($opt = mysqli_fetch_assoc($f_table)) {
                    echo '
                    <option value="'.$opt['id'].'"';
                      if ($akce == 'edit' && $opt['id'] == $editing[$search_var] && $vals[$v]['fkey'] == $table_test) {echo ' selected="selected"'; $selected = true;}
                    echo '>'; if ($addoner) {echo $opt['zkr'].' - ';} echo $opt['nazev'].'</option>';
                  }
            }
            /*
            if ($vals[$v]['sql'] == 'id_venue') {
              echo '<option value="-1"';
                if ($akce == 'edit' && $vals[$v]['values'] == -1) {echo ' selected';}
              echo '>[ONLINE FAMUFEST]</option>';
            }
            */
            if ($vals[$v]['sql'] != 'color' && $vals[$v]['sql'] != 'soutez') {
              echo '<option value="0"';
                if ((($vals[$v]['sql'] == 'id_blok' || $vals[$v]['sql'] == 'id_event' || isset($vals[$v]['values']) == 'blok,event') && $akce != 'edit') || ($akce == 'edit' && !$selected)) {echo ' selected';}
              echo '>-</option>';
            }
            echo '
            </select>';
          break;
          // CHECKBOX
          ///////////////
          case 'checkbox':
            if ($vals[$v]['fkey']) {
                $f_table = mysqli_query($conn, 'SELECT id, nazev FROM '.$vals[$v]['fkey']);
                $prk = 1;
                  while ($opt = mysqli_fetch_assoc($f_table)) {
                    echo '<input class="katOpt" type="checkbox" idkatr="'.$opt['id'].'" autocomplete="off" name="'.$vals[$v]['sql'].'"';
                      if ($akce == 'edit' && strpos($editing[$vals[$v]['sql']], '|'.$opt['id'].'|') !== false) {echo ' checked';}
                    echo '>'.$opt['nazev'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    if ($prk % 3 == 0) {
                      echo '<br>';
                    }
                    $prk++;
                  }
            } else {
            echo '<input type="checkbox" autocomplete="off" name="'.$vals[$v]['sql'].'"';
              if (($akce == 'edit' && $editing[$vals[$v]['sql']] == 1) || ($akce != 'edit' && $vals[$v]['sql'] == 'online')) {echo ' checked';}
            echo '>';
            }
          break;
          // DATE
          ///////////////
          case 'date':
            echo '<input type="date" autocomplete="off" name="'.$vals[$v]['sql'].'"';
              if (isset($id) && $akce == 'edit') {echo ' value="'.$editing[$vals[$v]['sql']].'"';}
            echo '>';
          break;
          // TIME
          ///////////////
          case 'time':
            echo '<input type="time" autocomplete="off" name="'.$vals[$v]['sql'].'"';
              if (isset($id) && $akce == 'edit') {echo ' value="'.$editing[$vals[$v]['sql']].'"';}
            echo '>';
          break;
          // TIME STAMP
          ///////////////
          case 'timestamp':
            echo '<input type="datetime-local" autocomplete="off" name="'.$vals[$v]['sql'].'"';
              if (isset($id) && $akce == 'edit') {
                if ($editing[$vals[$v]['sql']] != '' && $editing[$vals[$v]['sql']] != 'NULL') {
                  $tmstp = explode(" ", $editing[$vals[$v]['sql']]);
                  $tm_cas = explode(":", $tmstp[1]);
                  //echo ' value="'.$tm_dat[2].'/'.$tm_dat[1].'/'.$tm_dat[0].' '.$tm_cas[0].':'.$tm_cas[1].'"';
                  echo ' value="'.$tmstp[0].'T'.$tm_cas[0].':'.$tm_cas[1].'"';
                }
              }
            echo '>';
          break;
        }

        if ($vals[$v]['sql'] == 'stream_link') {

          echo '
          <div id="stream_tester">náhled</div>';
          if ($akce == 'edit') {
            echo '
            <script>
              setTimeout(function(){

                $(\'input[name="stream_link"]\').trigger("keyup");

              }, 500);
            </script>
            ';
          }

        }

        echo '</td></tr>';

      }

      echo '
      <tr>
        <td></td>
        <td><input id="saveButton" type="submit" name="'.$oblast.'" akce="'.$akce.'" db_id="'.$id.'" value="'; if ($akce == 'edit') {echo 'UPRAVIT';} else {echo 'PŘIDAT';} echo '"></td>
      </tr>
      ';

      echo '
      </table>
      </form>
      ';

      if ($oblast == 'news' || $oblast == 'mag' || $oblast == 'partneri' || $oblast == 'about' || $oblast == 'contacts' || $oblast == 'entrypage' || $oblast == 'pages') {
        echo '
        <script>

          function normalize(str) {
            str = str.replace(/’/g, "\'");
            str = str.replace(/\\\"/g, "\'");
            str = str.replace(/\\\/g, "");
            str = str.replace(/=\"/g, "=");
            str = str.replace(/\">/g, ">");
            return str;
          }

          function previewContent1() {

            editor_1.save().then((outputData) => {
              $.post(\'/php/json2html.php\', {data: outputData, oblast: "'.$oblast.'"}, function(res){
                $(\'#editor_preview_1\').html(res);
                var newdata = normalize(JSON.stringify(outputData));
                if (!outputData.blocks.length) {
                  newdata = \'\';
                }
                $(\'#editor_json_1\').val(newdata);
              });
            });

          }

          function previewContent2() {

            editor_2.save().then((outputData) => {
              $.post(\'/php/json2html.php\', {data: outputData, oblast: "'.$oblast.'"}, function(res){
                $(\'#editor_preview_2\').html(res);
                var newdata = normalize(JSON.stringify(outputData));
                if (!outputData.blocks.length) {
                  newdata = \'\';
                }
                $(\'#editor_json_2\').val(newdata);
              });
            });

          }

        </script>
        ';
      }

    break;

// DELETE
////////////////////////////////////////////////////////////////////////////////
    case 'delete':
      if (isset($id) && isset($oblast)) {

          if ($oblast == 'kategorie') {
            mysqli_query($conn, 'UPDATE event SET id_kat = 0 WHERE id_kat = "'.$id.'"');
          }

          if (mysqli_query($conn, 'DELETE FROM '.$oblast.' WHERE id = '.$id)) {
            echo '
            <script>
              page("/admin/'.$oblast.'");
            </script>
            ';
          }

      } else {
        echo '<h1>NENI OBLAST NEBO ID</h1>';
      }
    break;

// SWITCH
////////////////////////////////////////////////////////////////////////////////
    case 'switch':

        if (isset($id) && isset($oblast)) {

            $ids = explode("-", $id);
            $id1 = $ids[0];
            $id2 = $ids[1];

            if ($oblast == 'venue') {

              mysqli_query($conn, 'UPDATE program SET id_venue = 0 WHERE id_venue = '.$id1);
              mysqli_query($conn, 'UPDATE program SET id_venue = '.$id1.' WHERE id_venue = '.$id2);
              mysqli_query($conn, 'UPDATE program SET id_venue = '.$id2.' WHERE id_venue = 0');

            }

            if ($oblast == 'partneri_kat') {

              mysqli_query($conn, 'UPDATE partneri SET id_kat = 0 WHERE id_kat = '.$id1);
              mysqli_query($conn, 'UPDATE partneri SET id_kat = '.$id1.' WHERE id_kat = '.$id2);
              mysqli_query($conn, 'UPDATE partneri SET id_kat = '.$id2.' WHERE id_kat = 0');

            }

            if (
              mysqli_query($conn, 'UPDATE '.$oblast.' SET id = 0 WHERE id = '.$id1) &&
              mysqli_query($conn, 'UPDATE '.$oblast.' SET id = '.$id1.' WHERE id = '.$id2) &&
              mysqli_query($conn, 'UPDATE '.$oblast.' SET id = '.$id2.' WHERE id = 0')
            ) {

              echo '
              <script>
                page("/admin/'.$oblast.'/show");
              </script>
              ';

            }

        }

    break;
  }

  echo '
  </div>
  ';

}

?>
