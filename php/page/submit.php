<div class="pg_normal">
<?php
session_start();

include '../fce.php';
include '../sql_open.php';

  $ymdhis = date('Y-m-d H:i:s', time());
  $sql = 'SELECT
          page, page_en
          FROM entrypage
          WHERE rok = "'.$_SESSION['rok'].'" AND cas_od < "'.$ymdhis.'" AND cas_do > "'.$ymdhis.'"
          ORDER BY cas_od LIMIT 1';
  $contacts = mysqli_query($conn, $sql);

  if (mysqli_num_rows($contacts) > 0) {
    $contact = mysqli_fetch_assoc($contacts);

      $data = json_decode(lang($contact['page'], $contact['page_en']), true);
      json2html($data['blocks'], 'page');

  } else {

    echo '<h1>'.lang('NIC', 'EMPTY').'</h1>';

  }

include '../sql_close.php';
include '../document/lz.php';

?>
</div>
