<?php
session_start();

if ($_POST['login'] == 'dragon2' && $_POST['pass'] == 'spalenefazole') {

  $_SESSION['lord'] = true;
  $_SESSION['rok'] = date('Y');
  echo 'logged';

} else {

  echo 'wrong';

}

?>
