<?php
session_start();

  // ZVOLI DEFAULT JAZYK POKUD NENI ZVOLEN JINY
  include './php/sessions_load.php';

  // FUNKCE LANG()
  include './php/fce.php';

    // NAHRAJE HTML S JS
    include './php/document/layout.php';
    /*
    echo '
    <meta charset="utf-8">
    <h1>'.lang('OMLOUVÁME SE, PROBÍHÁ UPDATE WEBU', 'SORRY, WEBSITE IS BEING UPDATED').'</h1>
    <p>00.00 - 23.59 12. 10. 2020</p>
    ';
    */

/*
DB


  DB ROCNIK >
      EVENT (id, id_kategorie, id_blck, nazev, popis, thumb) => FILM / UDALOST
      KATEGORIE (id, nazev) => ANIMOVANY, HRANY, DOPROVODNY PROGRAM
      BLOKY (id, nazev) => A1, C3, HUDEBNI PROGRAM, VYHLASENI VITEZU
      VENUE (id, nazev, color)
      PROGRAM (id, id_type, id_event, id_venue, datum, cas_od, cas_do) => id_type = blok/event => id_event = id toho eventu
      NEWS (id, thumb)

      O FESTIVALU
      PARTNERI
      KONTAKT

      OPTIONS (homepage_bg, program_bg, )
      homepage (url)
      pages (id, url) => random se zvoli

  DB FAMUFEST_MASTER >
    USERS (id, login, pass, name)


*/

?>
