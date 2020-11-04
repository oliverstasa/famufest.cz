<div class="pg_normal">
<?php
session_start();

include '../fce.php';
include '../sql_open.php';

  $sql = 'SELECT
          page, page_en
          FROM contacts
          WHERE rok = "'.$_SESSION['rok'].'" ORDER BY timestamp DESC LIMIT 1';
  $contacts = mysqli_query($conn, $sql);

  if (mysqli_num_rows($contacts) > 0) {
    $contact = mysqli_fetch_assoc($contacts);

      $data = json_decode(lang($contact['page'], $contact['page_en']), true);
      json2html($data['blocks'], 'page');

  } else {

    echo '<h1>'.lang('ŽÁDNÉ KONTAKTY', 'NO CONTACTS').'</h1>';

  }

include '../sql_close.php';
include '../document/lz.php';

?>
<div class="content">
  <br>
  <a href="/data/FAMUFEST-Privacy-Policy.pdf" target="_blank" style="color: white;">Terms of Use, Privacy Policy</a>
</div>
<div class="content"><br></div>
</div>
