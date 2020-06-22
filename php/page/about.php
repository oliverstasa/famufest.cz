<?php
session_start();

include '../fce.php';
echo lang('
<div class="pg_normal">
  <h1>FESTIVALOVÉ VSTUPENKY</h1>
  <div class="content">
  Z bezpečnostních důvodů bude možné koupit vstupenky pouze přes síť <a href="https://goout.net/cs/festivaly/famufest-36-novy-termin/vhbye/" target="_blank">GoOut</a>. Při vstupu do kina se prokážete zakoupenou vstupenkou, která bude bezkontaktně ověřena. Na místě nebude probíhat žádný fyzický prodej vstupenek.

  <table class="cenova">
    <tr><td>Základní vstupenka</td><td>50&nbsp;Kč</td></tr>
    <tr><td>FAMU zlevněná vstupenka*</td><td>10&nbsp;Kč</td></tr>
  </table>
  <div class="small">* při vstupu bude požadováno prokázat se platnou kartou ISIC FAMU či jiným potvrzením o studiu</div>
  <br>
  Veškerý online program je ke zhlédnutí zdarma.
  </div>
  <h1>OTEVÍRACÍ DOBA KASÁREN KARLÍN</h1>
  <div class="content">
    <table>
      <tr><td>St 20. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>Čt 21. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>Pá 22. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>So 23. 5.&emsp;</td><td>10.00–23.30</td></tr>
      <tr><td>Ne 24. 5.&emsp;</td><td>10.00–23.30</td></tr>
    </table>
  </div>
  <h1>JAK SE DOSTAT NA FESTIVALOVÁ MÍSTA?</h1>
  <div class="content">
  Nejbližší zastávka hlavního dějiště FAMUFESTU je zastávka Florenc, kam se nejlépe dostanete:<br>
  tramvají:&emsp;<strong>3, 8, 24</strong><br>
  autobusem:&emsp;<strong>133, 135, 175, 194, 207, H1</strong><br>
  metrem:&emsp;<strong>B, C</strong>
  </div>
  <h1>DOTAZY</h1>
  <div class="content">
  V případě dotazů se obraťte na <a href="mailto:info@famufest.cz">info@famufest.cz</a>.
  </div>
  <h1>O FESTIVALU</h1>
  <div class="content">
    <p>FAMUFEST je multižánrový filmový festival, který každoročně pořádají studenti Filmové a televizní fakulty Akademie múzických umění v Praze. Umožňuje divákům mimo prostředí FAMU zhlédnout nejnovější tvorbu studentů. Jedním z dlouhodobých cílů festivalu je představovat kulturní veřejnosti nové talenty, které budou spoluvytvářet budoucnost české kinematografie, televizní tvorby, fotografie a dalších médií.</p>
    <p>Šestatřicátý ročník FAMUFESTU měl projít v původním zásadní proměnou. Stejně však jako celý kontinent i nás ochromila současná situace a museli jsme festival, původně plánovaný na konec března, odložit. Při pozvolném uvolňování opatření jsme se rozhodli tuto situaci využít a uspořádat plně bezpečný hybridně virtuální festival.</p>
    <p>Snažíme se především o maximální využití možností, jež současná doba nabízí, a tak mohou naši návštěvníci přecházet z virtuální projekce a koncert a zpět možností pár prokliků. Nechtěli jsme se však vzdát uvedení filmů tam, kam patří, v kinech. FAMUFEST proto k virtuálním aktivitám bude nabízet i jednu offlinovou, návštěvu kina v Kasárnách Karlín. Pokud patříte do skupiny mezi ty, kterým se po hmatatelném kulturním stýskalo, ale i ty, kteří nechtějí či nemohou opustit teplo domova, zastavte se (či si zaklikejte) na 36. FAMUFESTU a užijte si s námi spoustu studentských kraťasů, doprovodného programu a skvělé nálady, která bude panovat v karlínských kasárnách i na síti.</p>
  </div>
  <h1>POROTA</h1>
  <div class="content">
    <p>V letošní čtyřčlenné porotě posuzující soutěžní snímky zasedly kapacity napříč různými směry umělecké sféry. V porotě zasedli spisovatelka Anna Bolavá, umělkyně Lenka Klodová, "ještě větší kritik" Kamil Fila a předsedí jí herečka Klára Issová.</p>
    <p>Soutěž nerealizovaných scénářů se letos koná pod záštitou Dramaturgického inkubátoru Státního fondu kinematografie, a tak v porotě zasedají jeho členové: Eva Pjačíková, Milada Těšitelová a Vít Poláček.</p>
  </div>
</div>',
'
<div class="pg_normal">
  <h1>FESTIVAL TICKETS</h1>
  <div class="content">
  For safety reasons, it will only be possible to purchase tickets via the <a href="https://goout.net/cs/festivaly/famufest-36-novy-termin/vhbye/" target="_blank">GoOut</a> network. There will be no physical ticket sales on site.
  <table class="cenova">
    <tr><td>Basic ticket</td><td>50&nbsp;CZK</td></tr>
    <tr><td>FAMU discount*</td><td>10&nbsp;CZK</td></tr>
  </table>
  <div class="small">* upon entry, it will be required to provide a valid ISIC FAMU card or other confirmation of study</div>
  <br>
  All online program is free of charge.
  </div>
  <h1>OPENING HOURS OF KARLÍN BARRACKS</h1>
  <div class="content">
    <table>
      <tr><td>Wed 20. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>Thu 21. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>Fri 22. 5.&emsp;</td><td>13.00–23.30</td></tr>
      <tr><td>Sat 23. 5.&emsp;</td><td>10.00–23.30</td></tr>
      <tr><td>Sun 24. 5.&emsp;</td><td>10.00–23.30</td></tr>
    </table>
  </div>
  <h1>HOW TO GET THERE?</h1>
  <div class="content">
  The nearest stop is Florenc, where you can get to:<br>
  by tram:&emsp;<strong>3, 8, 24</strong><br>
  by bus:&emsp;<strong>133, 135, 175, 194, 207, H1</strong><br>
  by subway:&emsp;<strong>B, C</strong>
  </div>
  <h1>QUESTIONS</h1>
  <div class="content">
  In case of any questions, don’t hesitate to contact us via <a href="mailto:info@famufest.cz">info@famufest.cz</a>.
  </div>
  <h1>ABOUT FESTIVAL</h1>
  <div class="content">
    <p>FAMUFEST is a multi-genre film festival annually organized by students of the Film and Television Faculty of the Academy of Performing Arts in Prague. It allows viewers outside of FAMU environment to see the latest work of students. One of the long-term goals of the festival is to introduce new talents who will co-create the future of Czech cinematography, television production, photography and other media to the culturally aware public.</p>
    <p>The 36th year of FAMUFEST was to undergo a fundamental transformation in it’s original date. However, like the whole continent, we were paralyzed by the current situation and had to postpone the festival, which was originally planned for the end of March. With the gradual relaxation of the governmental measures, we decided to take advantage of this situation and organize a fully secure hybrid virtual festival.</p>
    <p>Above all, we strive to make the most of the possibilities that the this weird times offer, so our visitors can switch from virtual screenings and concerts and back on within few clicks. However, we did not want to give up screening movies in the place where they belong - in cinemas. Therefore, FAMUFEST will also offer one offline activity, a cinematic experience in Karlín barracks. If you are one of those who missed a tangible cultural experience, but as well one of those who do not want or cannot leave their home, stop by (or click) at the 36th FAMUFEST and enjoy a lot of student shorts, accompanying program and great the mood with us that will be in the Karlín barracks and online.</p>
  </div>
  <h1>JURY</h1>
  <div class="content">
    <p>In this year\'s four-member jury judging the competition films sat capacities from different directions of the artistic sphere. The jury consists of writer Anna Bolavá, artist Lenka Klodová, Kamil Fila (looking at things) and is chaired by actress Klára Issová.</p>
    <p>The competition of the unrealized scripts takes place this year under the auspices of the Dramaturgical Incubator of the State Fund of Cinematography, and so its members will sit in the jury: Eva Pjačíková, Milada Těšitelová and Vít Poláček.</p>
  </div>
</div>
');

?>
