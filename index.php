<?php
session_start();

  // ZVOLI DEFAULT JAZYK POKUD NENI ZVOLEN JINY
  include './php/sessions_load.php';

  // FUNKCE LANG()
  include './php/fce.php';

    // NAHRAJE HTML S JS
    include './php/document/layout.php';

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
