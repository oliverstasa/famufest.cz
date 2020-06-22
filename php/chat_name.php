<?php

if (isset($_GET['hash'])) {

    include './sql_open.php';

    $sql = 'SELECT name FROM chat_names WHERE hash = "'.$_GET['hash'].'"';

        $dotaz = mysqli_query($conn, $sql);
        if (mysqli_num_rows($dotaz) > 0) {
          $data = mysqli_fetch_array($dotaz);

            echo '
            <script src="/js/cookies.js"></script>
            <script>
              $.cookie("jmeno", "(s)'.$data['name'].'(/s)", { path: \'/\', expires: 999 });
              location.href = "/live";
            </script>
            ';
        }

    include './sql_close.php';

}

?>
