<?php
// helper
////////////////////////////////////////////////////////////////////////////////
function helfer($page, $cont = false) {

  $editor = '
  <ul>
    <li>nový řádek v textu: [shift]+[enter]</li>
    <li>ukončit blok: [enter] nebo kliknout na nový řádek</li>
    <li>nový řádek tabulky: [enter] nebo (+) po najetí za poslední řádek</li>
    <li>nová buňka: (+) po najetí ža poslední buňku</li>
    <li><i>text@text.xy</i> se automaticky změní na odkaz</li>
    <li>nabídka k vytvoření odkazu se zobrazí po vybrání textu</li>
    <li>nahrát lze pouze obrázek ve formátu .jpg</li>
  </ul>
  <video controls autoplay style=\'width: 50vh;\'>
    <source src=\'/data/help/editor1.mp4\' type=\'video/mp4\' poster=\'/data/img/lasergirl_pixel.gif\'>
  </video>
  ';

  switch($page) {
    case 'intro':
      $help = array(array('html' => '
      <img src=\'/data/help/h1.png\' style=\'width: 60vh;\'>
      (!) Pokud kterákoli z nabídek není vyplněna žádným obsahem, nezobrazí se vůbec v menu<br>
      <ul>
        <li><strong>2020 (YYYY)</strong> — přepíná administraci do požadovaného ročníku, tj. všechny akce v jednotlivých sekcích se ukládají do uvedeného ročníku</li>
        <li><strong>ROČNÍK</strong> — správa jednotlivých ročníků festivalu, přidává úvodní video, og:image a popisek v pr. dolním rohu</li>
        <li><strong>REŽIM</strong> — přepíná mezi <i>základním režimem</i> a <i>filmotékou</i>, tj. režimem kde jsou filmy přístupné ke shlédnutí online, tl. <i>FILMOTÉKA</i> se v menu zobrazí místo tl. <i>FILMY</i> pokud je tento režim aktivní a filmy v databázi splňují kritéria pro přehrání online</li>
        <br>
        <li><strong>KINO</strong> — správa online streamu, tl. <i>ŽIVĚ</i> v menu se zobrazí automaticky pokud probíhá stream</li>
        <li><strong>CHAT</strong> — vytváření zvýrazněných profilů pro chat</li>
        <br>
        <li><strong>BLOKY</strong> — správa fetivalových bloků</li>
        <li><strong>VENUES</strong> — správa fetivalových venues, tj. míst, ve kterých probíhá program</li>
        <li><strong>PROGRAM</strong> — správa programu</li>
        <br>
        <li><strong>EVENTY</strong> — správa eventů konajících se v programu, tj. event = film/událost (událost je např. slavnostní zahájení, apod.)</li>
        <li><strong>KATEGORIE FILMŮ</strong> — správa kategorií filmů (stejné pro každý rok)</li>
      </ul>
      ', 'name' => 'Intro do administrace'));
    break;
    case 'rok':
      $help = array(array('html' => '
      <ul class=\'arr\'>
        <li><strong>[ROČNÍK]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>PUBLIKOVÁNO</strong> — ročník není/je přístupný pro návštěvníky webu a zobrazuje se v archivu</li>
            <li><strong>ROČNÍK</strong> — rok (YYYY), např.: <i>2021, 2028, ...</i></li>
            <li><strong>TERMÍN</strong> — všudypřítomný text zobrazující se v pravém dolním rohu webu, např.: <i>XXth FILM FESTIVAL FAMUFEST XX.—XX.XX.</i></li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>V SEZNAMU ROČNÍKŮ</strong> se nahrávají následující data:
          <ul>
            <li><strong>OG</strong> — obrázek pro sociální sítě, pouze formát .jpg</li>
            <li><strong>BG/THUMB</strong> — pozadí webu pro mobilní zařízení na úvodní obrazovce, sloužící zároveň jako obrázek zobrazený před nahráním úvodního videa, pouze formát .jpg</li>
            <li><strong>VIDEO</strong> — video na úvodní obrazovce webu, pouze formát .mp4, maximální doporučená velikost 10mb</li>
          </ul>
        </li>
        <li><strong>ROČNÍK ZALOŽEN ✓</strong></li>
      </ul>
      ', 'name' => 'Založit ročník'), array('html' => '
      <ul class=\'arr\'>
        <li><strong>[ROČNÍK]</strong></li>
        <li><strong>V SEZNAMU ROČNÍKŮ</strong>
          <ul class=\'arr\'>
            <li><strong>UJISTIT SE</strong> — že jsou vyplněny všechny informace k ročníku a nahrány všechny správné média, pokud nejsou, upravit informace: [✎], upravit média kliknutím na okno s požadovaným médiem</li>
            <li><strong>UJISTIT SE</strong> — že je ročník VEŘEJNÝ (<i>PUBLIKOVÁNO</i>), pokud není, upravit [✎]</li>
            <li><strong>[AKTIVOVAT]</strong> — kliknout na tl. [AKTIVOVAT]</li>
          </ul>
        </li>
        <li><strong>ROČNÍK AKTIVOVÁN ✓</strong></li>
      </ul>
      ', 'name' => 'Aktivace ročníku'));
    break;
    case 'program':
      $help = array(array('html' => '
      Program se skládá z několika částí, které jsou nutné k jeho úspěšnému vyplnění, tyto části jsou: CO? KDE? KDY?
      <ul class=\'arr\'>
        <li><strong>CO? [EVENTY]/[BLOKY]</strong> — jakákoli vytvořená událost či blok událostí odehrávající se v tomto ročníku</li>
        <li><strong>KDE? [VENUES]</strong> — jakákoli vytvořená venue odehrávající se v tomto ročníku</li>
        <li><strong>KDY?</strong> — aby se program zobrazoval, je nutné vědět, kdy se bude konat: rok, měsíc, den, hodinu, minutu</li>
        <li><strong>POKUD MÁTE TYTO ÚDAJE, POKRAČUJTE DO TVORDY PROGRAMU ✓</strong>
      </ul>
      ', 'name' => 'Před vytvořením programu'), array('html' => '
      <ul class=\'arr\'>
        <li><strong>[PROGRAM]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>TYP</strong> — jedná se o [EVENT] nebo o [BLOK]?</li>
              <ul>
                <li><strong>[EVENT]</strong> — jedná se o jednotlivou událost, tzn. např. o \'slavnostní zahájení\' atp., ze seznamu událostí tedy vyberte požadovanou událost</li>
                <li><strong>[BLOK]</strong> — jedná se o blok událostí, tzn. např. blok \'Dlouhé bydlo\' atp., ze seznamu bloků vyberte požadovaný blok</li>
              </ul>
            <li><strong>ONLINE</strong> — tato událost a nebo celý blok událostí bude/nebude přístupný online = zobrazovat se ve filmotéce
              <ul>
                <i>NEBUDE ONLINE</i>:<br>
                <li><strong>[OFFLINE]</strong> — nebude ONLINE, prostě se jen zobrazí v programu</li>
                <i>BUDE ONLINE</i>:<br>
                (!) tyto události bude možné přehrát jen tehdy, pokud <u>je aktivní [REŽIM] filmotéka</u>, je u události <u>vyplněn link k přehrání</u> a uživatel splňuje <u>geolokační požadavky</u><br>
                <li><strong>[ONLINE PO 24H]</strong> — online od nejbližší následující půlnoci po dobu 24h (tzn. např. program se koná 20.10, online bude celých 24h 21.10)</li>
                <li><strong>[ONLINE IHNED]</strong> — online celý aktuální den konání programu (tzn. např. program se koná 20.10, online bude celý den 20.10)</li>
              </ul>
            </li>
            <li><strong>VENUE</strong> — kde se bude film odehrávat? (v případě online projekcí založte VENUE s názvem vyjadřujícím, že jde o online venue)</li>
            <li><strong>DATUM</strong> — který programový se tento program odehrává (např. párty konající se 21.10 v 0:10 pořád programově spadá do dne 20.10, tzn. v takovém případě zde uvést 20.10)</li>
            <li><strong>ZAČÁTEK</strong> — přesný začátek programu</li>
            <li><strong>KONEC</strong> — přesný závěr programu</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>PROGRAM PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Tvorba programu'), array('html' => '
      <ul>
        <li><strong>TVORBA ONLINE PROGRAMU</strong>
          <ul>
            <li>PŘÍKLAD 1: 29.10 hraje blok A1 v kinech a chci, aby následující den byl celý blok přístupný online:
              <ul>
                <li>Všechny filmy uvnitř bloku A1 mají vložený [VIMEO LINK]</li>
                <li>V programu je nastavena možnost [ONLINE PO 24H]</li>
                <li>[REŽIM] je nastavený na variantu [FILMOTÉKA] od 29.10 do 30.10</li>
              </ul>
            </li>
            <li>PŘÍKLAD 2: chci, aby se 22.10 odehrával blok A2 pouze online po celý den:
              <ul>
                <li>Všechny filmy uvnitř bloku A2 mají vložený [VIMEO LINK]</li>
                <li>V programu je nastavena možnost [ONLINE IHNED]</li>
                <li>[REŽIM] je nastavený na variantu [FILMOTÉKA] od 22.10 do 23.10</li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
      ', 'name' => 'Program: příklady online programu'));
    break;
    case 'event':
      $help = array(array('html' => '
      K vytvoření události může být nutné nejprve vytvořit jiné složky systému, a je také dobré ujasnit si vlastnosti vkládané události, k jejímu vytvoření však nejsou žádné z nich povinné (lze je vyplnit později):
      <ul>
        <li><strong>[BLOK]</strong> — bude se událost odehrávat v rámci nějakého bloku? pokud ano, je ten blok už vytvořený?</li>
        <li><strong>TYP=>FILM</strong> — pokud se jedná o vytvoření události typu FILM, je nutné jej zařadit do kategorie - je tato kategorie známa a existuje v systému?</li>
        <li><strong>VIMEO LINK</strong> — bude se tato událost (film) přehrávat online? pokud ano, je už film nahraný na vimeu?</li>
        <li><strong>GEOBLOK ČR</strong> — pokud se bude přehrávat online, jsou práva na přehrání i mimo čr?</li>
      </ul>
      ', 'name' => 'Před vytvořením události'), array('html' => '
      <ul class=\'arr\'>
        <li><strong>[EVENTY]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>TYP</strong> — jedná se o [FILM] nebo o [EVENT]?</li>
              <ul>
                <li><strong>[FILM]</strong>
                  <ul>
                    <li><strong>KATEGORIE</strong> — do jaké kategorie se film řadí? (např. hraný film, atp.)</li>
                  </ul>
                </li>
                <li><strong>[EVENT]</strong> — událost, tzn. např. \'slavnostní zahájení\', \'doprovodný program\' atp.</li>
              </ul>
            <li><strong>BLOK</strong> — pokud je událost součástí nějakého bloku, vyberte blok, pokud ne, nechte pole prázdné</li>
            <li><strong>ARAMISOVA CENA</strong> — pokud je zároveň film součástí soutěže o aramisovu cenu, zaškrtnout</li>
            <li><strong>DÉLKA</strong> — délka filmu uvedena pouze v minutách, tzn. správně: \'14\', špatně: \'14.23\'</li>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>ODKAZ</strong> — podle jaké adresy se bude dát na událost odkazovat: www.famufest.cz/(films/events)/[ODKAZ]</li>
            <li><strong>VIMEO LINK</strong> — odkaz na přehrání filmu z FAMU VIMEO účtu - nutno vyplnit, aby šel film přehrát ve filmotéce</li>
            <li><strong>GEOBLOK ČR</strong> — pokud lze film přehrát online pouze pro uživatele uvnitř ČR, zaškrtnout</li>
            <li><strong>OBSAH CZ</strong> — popisek k události/filmu - pokud vyplňujete popisek k filmu, začněte vyplňovat ho česky, nejdříve popisek a poté na nový řádek pod popisek pro vložení štábu stiskněte tl. [vložit štábovou šablonu], vloží se základní šablona, která bude přepisovat pozice i do EN verze i s překladem = šetří čas</li>
            <li><strong>OBSAH EN</strong> — cokoli napíšete do této části se zpětně nepřepíše do CZ verze</li>
            <li><strong>[PŘIDAT]</strong></li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>SEZNAM EVENTŮ — NAHRÁT FOTKU</strong> — v seznamu eventů stačí kliknout na nahrávací ikonu v kolonce [THUMB] a nahrát fotku (ve formátu .jpg), tato fotka slouží jako OG:image pro soc. sítě při sdílení filmu a zobrazuje se jako background po rozkliknutí filmu</li>
        <li><strong>EVENT PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Tvorba událostí'));
    break;
    case 'blok':
      $help = array(array('html' => '
      <ul class=\'arr\'>
        <li><strong>[BLOKY]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>ZKRATKA</strong> — zkrácený název bloku, např. A1</li>
            <li><strong>ODKAZ</strong> — jak se bude na blok odkazovat: www.famufest.cz/programme/block/[ODKAZ]</li>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>[PŘIDAT]</strong></li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>BLOK PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Tvorba bloku'));
    break;
    case 'venue':
      $help = array(array('html' => '
      Pořadí venues určuje i pořadí ve výpisu na stránce [PROGRAM]<br>
      <ul class=\'arr\'>
        <li><strong>[VEUES]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>ODKAZ</strong> — jak se bude na blok odkazovat: www.famufest.cz/programme/block/[ODKAZ]</li>
            <li><strong>ADRESA</strong> — obyčejná poznávací adresa, např. <i>Haškova 14, Praha</i> => v programu se z ní vygeneruje odkaz na google maps</li>
            <li><strong>BARVA</strong> — vyberte originální barvu pro každou venue, veškerý program odehrávající se v této venue ponese tuto barvu</li>
            <li><strong>[PŘIDAT]</strong></li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VENUE PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Tvorba venue'));
    break;
    case 'news':
      $help = array(array('html' => '
      <ul class=\'arr\'>
        <li><strong>[NOVINKY]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>PUBLIKOVÁNO</strong> — zaškrtněte, pokud je novinka připravena a má se zobrazovat veřejnosti (i když je vyplněn čas od kdy se zobrazuje, ale není publikována = nebude se zobrazovat)</li>
            <li><strong>OD KDY</strong> — datum a čas od kdy se novinka začne zobrazovat na webu, pokud je zároveň publikována</li>
            <li><strong>NÁZEV CZ/EN</strong> — název by měl obsahovat alespoň 20 znaků, aby novinka vypadala dobře když putuje v úvodním pruhu na webu, pokud název události bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>ODKAZ</strong> — jak se bude na novinku odkazovat: www.famufest.cz/news/[ODKAZ]</li>
            <li><strong>OBSAH</strong> — viz [[?] PRÁCE S EDITOREM]</li>
            <li><strong>[PŘIDAT]</strong></li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>SEZNAM NOVINEK — NAHRÁT FOTKU</strong> — v seznamu novinek stačí kliknout na nahrávací ikonu v kolonce [THUMB] a nahrát fotku (ve formátu .jpg), tato fotka slouží jako OG:image pro soc. sítě při sdílení novinky a zobrazuje se jako background po rozkliknutí novinky</li>
        <li><strong>NOVINKA PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Tvorba novinky'), array('html' => $editor, 'name' => 'Práce s editorem'));
    break;
    case 'settings':
      $help = array(array('html' => '
      <ul class=\'arr\'>
        <li><strong>[REŽIM]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>REŽIM</strong> — nestačí pouze spustit tento režim pro zobrazení filmů s linkem online, tato funkce je vázána na to, jak jsou v programu nastaveny podmínky pro přehrání
              <ul>
                <li><strong>filmotéka</strong> — aktivuje možnost přehrávat filmy online (pokud jsou řádně nastaveny v programu) od data+času do data+času</li>
                <li><strong>default</strong> — klasické konání festivalu - není třeba nastavovat, jakmile skončí [filmotéka], je spuštěn automaticky</li>
              </ul>
            </li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>REŽIM PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Přepínání režimu filmotéky'));
    break;
    case 'kino':
      $help = array(array('html' => '
      <ul class=\'arr\'>
        <li><strong>[KINO]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>ODKAZ NA STREAM</strong> — odkaz na už běžící livestream (možno vyplnit i později); okno náhledu slouží ke kontrole, jestli zvolený odkaz funguje - pokud se zobrazí a funguje v náhledu, bude fungovat i na ostro; varianty streamů:
              <ul>
                <li>YOUTUBE (technicky nejlepší řešení) — např. [https://www.youtube.com/watch?v=XS088Opj9o0] nebo [https://youtu.be/XS088Opj9o0]</li>
                <li>VIMEO — např. [https://vimeo.com/376880888]</li>
                <li>FACEBOOK (tehcnicky nejhorší řešení) — např. [https://www.facebook.com/watch/?v=275630553121328] nebo [https://www.facebook.com/highedition/videos/2661865314036684/]</li>
              </ul>
            </li>
            <li><strong>ZAPNE SE</strong> — čas, kdy se na stránce [LIVE] (v menu) přestane odpočítávat a místo odpočtu se objeví okno se streamem</li>
            <li><strong>ZPŘÍSTUPNÍ SE</strong> — čas, od kdy se v menu objeví tl. [LIVE] - stránka bude obsahovat odpočet do spuštění streamu a aktivní chat, kde bude možno debatovat a psát oznámení</li>
            <li><strong>UZAVŘE SE</strong> — čas, kdy se kompletně uzavře i chat i stránka [LIVE] - doporučuju nechat aspoň hodinu po skončení streamu místnost pořád otevřenou: může pokračovat diskuse v chatu, apod.</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>REŽIM [LIVE] PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Vytvoření vysílání'), array('html' => '
      <ul>
        <li><strong>POPRVÉ</strong> — pokud to děláte poprvé, doporučuju vyzkoušet si to na ostro někdy před festivalem spustit třeba na hodinu někdy v noci, abyste si vyzkoušeli jak to celé pracuje a na ostro to bylo vše ok, jinak je tady zbytečná eventualita trapasu</li>
        <li><strong>CHAT ANNOUNCER</strong> — v nabídce [CHAT] je dobrý nastavit si nějakej vlastní announcer (třeba FAMUFEST TEAM, atp.) a pomocí něj psát do chatu věci jako \'vítáme vás\', \'díky za pozornost\', atp.</li>
        <li><strong>FACEBOOK</strong> — stream z facebooku funguje, ale silně ho nedoporučuju, je tam největší pravděpodobonst že to bude vypadat na každém zařízení jinak, to je něco co nemohu nastavit, to si nastavuje sám fb a dělá to ne vždy dobře = takový stream vypadá ne-profi; YOUTUBE i VIMEO jsou bez problému</li>
      </ul>', 'name' => 'Vysílání: tipy'));
    break;
    case 'chat_names':
      $help = array(array('html' => '
      Tato funkce nabízí možnost vytvářet barevně odlišené jména pro chat, což se hodí např. pro announcer, nebo pro autory kteří chtějí diskutovat o svých filmech přehrávaných ve streamu, popř. ověřené (fyzicky neúčasné) diskutéry vyjadřující se k streamované diskusi, tyto jména se aktivují pomocí zaslání speciálního odkazu a jsou narozdíl od ostatních červené<br>
      <ul class=\'arr\'>
        <li><strong>[CHAT]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>JMÉNO</strong> — přesné znění jména, které bude uživateli přiděleno do chatu</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>ZVÝRAZNĚNÉ JMÉNO PŘIDÁNO ✓</strong></li>
        <li><strong>V SEZNAMU JMEN</strong> — kliknout na [ZKOPÍROVAT ODKAZ] => odkaz se zkopíruje do schránky a stačí jej vložit do mailu/chatu a odeslat určené osobě</li>
      </ul>', 'name' => 'Používání chatu'));
    break;
    case 'kategorie':
      $help = array(array('html' => '
      Pro případ, že by přibyla pro nějaký ročník speciální kategorie, dávám tu možnost ji přidat, původní kategorie ale není možné editovat; kategorie se v nabídce filmů zobsazují pouze tehdy, pokud je aspoň jeden film, který do dané kategorie spadá<br>
      <ul class=\'arr\'>
        <li><strong>[KATEGORIE FILMŮ]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
            <li><strong>ODKAZ</strong> — jak se bude na kategorii odkazovat: www.famufest.cz/category/[ODKAZ]</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>KATEGORIE PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Tvorba kategorie pro film'));
    break;
    case 'partneri':
      $help = array(array('html' => '
      Nejdříve je nutno vytvořit kategorii pro partnery, než se přidá konkrétní partner<br>
      <ul class=\'arr\'>
        <li><strong>[PARTNEŘI]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>KATEGORIE</strong> — do které kategorie partner patří (pokud jeden partner patří do více kategorií, nahrajte ho zvlášť - duplicitně)</li>
            <li><strong>NÁZEV</strong> — název partnera (pokud nebude k partnerovi nahrán obrázek, bude se zobrazovat pouze tento text)</li>
            <li><strong>ODKAZ</strong> — web partnera</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>V SEZNAMU PARTNERŮ</strong> — pro nahrání loga, kliknout ve sloupci [LOGO] na nahrávací ikonu</li>
        <li><strong>NAHRÁT LOGO</strong> — logo MUSÍ být:
          <ul>
            <li>100% BÍLÉ (žádné barvy = čistý pozitiv)</li>
            <li>TRANSPARENTNÍ (optimálně = není nutno)</li>
            <li>ve formátu .PNG</li>
          </ul>
          (nedodržení těchto podmínek povede ke grafické sebevraždě)<br>
        <li><strong>PARTNER PŘIDÁN ✓</strong></li>
      </ul>', 'name' => 'Správa partnerů'));
    break;
    case 'partneri_kat':
      $help = array(array('html' => '
      Kategorie do kterých budou partneři spadat, např. \'Hlavní partneři festivalu\', atp.<br>
      <ul class=\'arr\'>
        <li><strong>[KATEGORIE PARTNERŮ]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>NÁZEV CZ/EN</strong> — pokud název bude vyplněn jen v jednom jazyce, vždyc se zobrazí pouze vyplněná verze, je ale nutno vyplnit název alespoň v jednom jazyce</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>KATEGORIE PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Správa kategorií partnerů'));
    break;
    case 'contacts':
      $help = array(array('html' => '
      Stránka s kontakty na festivalový tým; vždy poslední přidaná verze se aktuálně zobrazuje<br>
      <ul class=\'arr\'>
        <li><strong>[KONTAKTY]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>OBSAH</strong> — viz [[?] PRÁCE S EDITOREM]</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VERZE STRÁNKY PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Stránka KONTAKT'), array('html' => $editor, 'name' => 'Práce s editorem'));
    break;
    case 'about':
      $help = array(array('html' => '
      Stránka s informacemi o festivalu; vždy poslední přidaná verze se aktuálně zobrazuje<br>
      <ul class=\'arr\'>
        <li><strong>[O FESTIVALU]</strong></li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VYPLNIT</strong>:
          <ul>
            <li><strong>OBSAH</strong> — viz [[?] PRÁCE S EDITOREM]</li>
          </ul>
        </li>
        <li><strong>[PŘIDAT]</strong></li>
        <li><strong>VERZE STRÁNKY PŘIDÁNA ✓</strong></li>
      </ul>', 'name' => 'Stránka O FESTIVALU'), array('html' => $editor, 'name' => 'Práce s editorem'));
    break;
  }

  for ($h = 0; $h < sizeof($help); $h++) {
    $ress .= '<div class="help" html="'.$help[$h]['html'].'" nazev="'.$help[$h]['name'].'">[?] '.$help[$h]['name'].'</div>';
  }

  echo $ress;

  if ($cont === true) {
    echo '
    <div id="helpvideo">
    <h1></h1>
    <div id="navod"></div>
    </div>
    ';
  }

}
?>
