<?php
session_start();

include '../fce.php';

if ($_SESSION['kino'] > 0) {

  include '../sql_open.php';

  $now = date('Y-m-d H:i:s', time());
  $sql = 'SELECT stream_link, nazev, nazev_en, cas_spusteni, cas_do
          FROM kino
          WHERE cas_od < "'.$now.'" AND cas_do > "'.$now.'"';
  $dotaz = mysqli_query($conn, $sql);
  if (mysqli_num_rows($dotaz) > 0) {
    $data = mysqli_fetch_array($dotaz);

    echo '

    <script type="text/javascript" src="/js/livecounter.js"></script>
    <script src="/js/cookies.js"></script>
    <script src="/js/chat.js"></script>
    <script>';

    echo $ted10 = strtotime(date('Y-m-d H:i:s', strtotime("+10 minutes")));
    echo $ted1 = strtotime(date('Y-m-d H:i:s', strtotime("+1 minutes")));
    echo $konec = strtotime($data['cas_do']);

    $za10minkonec = ($konec-$ted10)*1000;
    $za1minkonec = ($konec-$ted1)*1000;
    if ($za10minkonec > 10*60000) {

    echo '
      setTimeout(function(){

        $(\'#feed\').append(\'<div class="info_nobreak"><center>\'+jazyk(\'Za 10 min se uzevře livestream.\', \'Livestream will close in 10 min.\')+\'</center></div>\');

        var vyskabar = $(\'#feed\').prop(\'scrollHeight\')-$(\'#feed\').outerHeight();
        $(\'#feed\').animate({scrollTop: vyskabar}, 250);

      }, '.$za10minkonec.');
    ';

    }

    if ($za1minkonec < 60000) {$za1minkonec = 3000;}

    echo '
        setTimeout(function(){

          $(\'#feed\').append(\'<div class="info_nobreak"><center>\'+jazyk(\'Za 1 min se uzevře livestream.\', \'Livestream will close in 1 min.\')+\'</center></div>\');

          var vyskabar = $(\'#feed\').prop(\'scrollHeight\')-$(\'#feed\').outerHeight();
          $(\'#feed\').animate({scrollTop: vyskabar}, 250);

        }, '.$za1minkonec.');
    </script>
    <style>
      @media screen and (max-aspect-ratio: 3/2) {

        /* #content {padding: 0; width: 100%; padding-top: 9vh;} */

      }
    </style>
    <div id="chatheight"></div>

    <div id="platno">
      <div class="video">
    '; // '; if ($data['cas_spusteni'] <= $now && $data['stream_link']) {echo ' loading';} echo '

    $nazev = lang($data['nazev'], $data['nazev_en']);

    if ($data['cas_spusteni'] <= $now) {

      if (!$data['stream_link']) {
        echo '<div id="streamidle">'.lang('CHYBA STREAMU', 'STREAM ERROR').' <a href="live">'.lang('ZKUSIT ZNOVA', 'TRY AGAIN').' ↺</a></div>';
      } else {
        $odkaz_na_video = $data['stream_link'];
      }

    } else {
      $odkaz_na_video = 'none';
    }

    include '../stream_linker.php';

    echo '
      </div> <!-- 376880888 -->
      <div class="chat">
        <div id="feed">
        </div>
        <div id="msg">
          <form>
            <input type="text" placeholder="'.lang('Napsat zprávu', 'Type a message').'">
            <div class="submit">
              <input type="submit" value="'.lang('Odeslat', 'Send').'">
            </div>
          </form>
        </div>
        <div id="enter_name">
          <form>
            <input type="text" maxlength="25" class="taken">
            <div class="label">'.lang('Jméno', 'Name').':</div>
            <div class="submit">
              <input type="submit" value="'.lang('Uložit', 'Save').'">
            </div>
          </form>
        </div>
      </div>
      <h1>'.$nazev.' <span id="livecount_oko"><div class="oko"></div>&nbsp;<span id="livecount">0</span></span></h1>
    </div>
    ';

  } else {

    echo '<h1>'.lang('FAMUFEST nyní nevysílá', 'FAMUFEST is now offline').'</h1>';

  }

  include '../sql_close.php';

} else {

  echo '<h1>'.lang('FAMUFEST nyní nevysílá', 'FAMUFEST is now offline').'</h1>';

}

?>
