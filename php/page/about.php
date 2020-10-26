<div class="pg_normal">
<?php
session_start();

include '../fce.php';
include '../sql_open.php';

  $sql = 'SELECT
          page, page_en
          FROM about
          WHERE rok = "'.$_SESSION['rok'].'" ORDER BY timestamp DESC LIMIT 1';
  $about = mysqli_query($conn, $sql);

  if (mysqli_num_rows($about) > 0) {
    $aboutr = mysqli_fetch_assoc($about);

      $data = json_decode(lang($aboutr['page'], $aboutr['page_en']), true);
      if ($data['blocks'] != '') {
        json2html($data['blocks'], 'page');
      }

  } else {

    echo '<h1>'.lang('NEVYPLNĚNO', 'EMPTY').'</h1>';

  }

include '../sql_close.php';
include '../document/lz.php';

?>
</div>
