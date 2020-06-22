<?php
session_start();
include '../fce.php';

    //$msg = lang('Tento web používá cookies. Pokračováním v prohlížení stránky souhlasíte s jejich použitím.', 'This website uses cookies. By scrolling or continuing to browse otherwise, you agree to the use of cookies.');
    $msg = lang('POUŽÍVÁME COOKIES', 'WE USE COOKIES');
    $acc = lang('SCHVALUJI', 'I APPROVE');
    $pol = lang('ZJISTIT VÍCE', 'READ MORE');

    echo '
    <script src="/js/cookie.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
         $.cookieBar({
          message: "'.$msg.'",
          acceptButton: true,
          acceptText: "'.$acc.'",
          declineButton: false,
          policyButton: true,
          policyText: "'.$pol.'",
          policyURL: "/data/FAMUFEST-Privacy-Policy.pdf",
          autoEnable: true,
          acceptOnContinue: false,
          acceptOnScroll: false,
          acceptAnyClick: false
         });
         ';
         if ($_GET['uri'] != '/') {
           echo '
           $(\'#cookie\').addClass(\'page\');
           ';
         }
         echo '
      });
    </script>
    ';

?>
