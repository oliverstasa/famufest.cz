<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta property="og:image" content="web.jpg">
  <title>FAMUFEST.CZ — Analytika</title>
  <script type="text/javascript" src="jq.js"></script>
  <script type="text/javascript">
    $(window).load(function(){

        $("#loading").fadeOut(500);

        $('.blok').each(function(i){
          var div = $(this);
          setTimeout(function(){
            div.removeClass('hidden');
          }, i*100);
        });

    });
  </script>
  <style>
    @font-face {font-family: "regular"; src: url("./font.woff");}
    html, body {margin: 0; padding: 0vh; font-family: regular; font-size: 2vh;}
    #graf {padding: 5vh; width: calc(50% - 5vh); height: auto;}
      html::-webkit-scrollbar-track {background-color: transparent;}
      html::-webkit-scrollbar {width: 1vh; height: 1vh; background-color: transparent;}
      html::-webkit-scrollbar-thumb {background-color: black;}
    table {border-collapse: collapse;}
    table tr td, table tr th {padding: 1vh 3vh;}
      table tr th {color: gray; text-align: center;}
        table tr th:first-child {text-align: left;}
        table tr th:last-child {text-align: right;}
    ul {list-style: none; counter-reset: bboy; display: list-item; flex-wrap: wrap;}
    li {counter-increment: bboy; margin-bottom: 2vh;}
      li:last-child {margin-bottom: 0;}
    li::before {content: "→ ";}
    table span, li span {color: blue;}
    table.desc {color: gray; font-size: 1.3vh;}
      table.desc tr td {padding: 0.5vh 2vh;}
    a, a:hover {color: blue; text-decoration: underline;}
    .blok {transition: all 0.5s ease; transform-origin: center center; opacity: 1; transform: scale(1);}
      .hidden {opacity: 0; transform: scale(0.95);}
    #loading {position: fixed; top: 0; width: 100%; min-height: 0.25vh; background: linear-gradient(to right, transparent 90%, red 10%); background-size: 200% 100%; animation: loading 2s infinite; animation-delay: -0.2s; z-index: 10;}

    @keyframes loading {
        0%   {background-position: 220% 0%;}
        100% {background-position: 80% 0%;}
    }

    @media screen and (max-aspect-ratio: 3/2) {
      #graf {width: calc(100% - 10vh);}
    }
  </style>
</head>
<body>
<div id="loading"></div>
<div id="graf">
  <div class="blok hidden">
  <h1 style="margin-top: 0;">FAMUFEST.CZ — Analytika</h1>
  <p>Interpretovaná doba měření 1. — 30. 5. 2020</p>
  </div>
  <div class="blok hidden">
  <h1>Abstract</h1>
  <p>
    <ul>
      <li>Během festivalu došlo k <span>10,684</span> online přehrání filmů* <span>2,404</span> návštěvníky</li>
      <li>Web navštívilo <span>10,106 návštěvníků</span> a 3/4 z nich se na web vraceli</li>
      <li><span>451 návštěvníků</span> si udělalo domácí festival, tvoří <span>20% celkového provozu</span> na webu**, strávili na něm v online fázi průměrně <span>1.5h/den/návštěvník</span> a viděli alespoň 4 filmy denně</li>
      <li>Většina diváků, kteří viděli 1 celý film, se dále podívali alespoň na 2 další filmy</li>
      <li>Hlavní referenční kanál byl <span>Facebook</span>: 1/2 návštěv přišla z něj, 1/4 přímo na web, 1/5 z google vyhledávače</li>
      <li>90% návštěvníků z česko—slovenska, zbytek z 63 zemí</li>
      <li>Mobil <i>vs</i> počítač: návštěvy webu — 50:50, sledování filmů — 20:80</li>
      <li><span>FUN FACT</span> ze zahraničí: jeden návštěvník ze Stockholmu strávil na webu každý vysílací den 3 a více hodin, a viděl téměř všechny dostupné filmy 💖</li>
    </ul>
    <table class="desc">
      <tr><td>*</td><td>Ano, opravdu, tolik unikátních přehrání do μ <span>70%</span> jednotlivého filmu, z toho <span>2,019 do konce</span> (tj. do poslední sekundy), a ve <span>20,525</span> instancích si film divák alespoň na chvíli pustil</td></tr>
      <tr><td>**</td><td>Z hlediska hloubky návštěvy tito uživatelé prošli <span>≈12 úrovní stránek/návštěva/uživatel</span> = hluboký zájem, nejméně aktivní navštíví 0—3 úrovně, ti nejaktivnější 30—60</td></tr>
    </table>
  </p>
  </div>
  <div class="blok hidden">
  <h1>Data</h1>
  <table>
    <tr><th>Zdroj dat</th><th>Data</th></tr>
    <tr><td>Google Analytics*</td><td>Základní metriky <a href="google.csv" target="_blank">.CSV</a>, Přehled <a href="ZAKLAD.pdf" target="_blank">.PDF</a></td></tr>
    <tr><td>Facebook Insights</td><td>Všechny data <a href="fb.csv" target="_blank">.CSV</a>, <a href="FB_ALL.xls" target="_blank">.XLS</a></td></tr>
    <tr><td>Vimeo Analytics</td><td>Videa <a href="stats_export_data.csv" target="_blank">.CSV</a>, Mapa <a href="stats_export_map.csv" target="_blank">.CSV</a></td></tr>
  </table>
  <table class="desc">
    <tr><td>*</td><td>Všechna data nelze vyexportovat, pro poskytnutí vstupu do analytických nástrojů pro další metriky mě kontaktujte na <a href="mailto: oliver.stasa@gmail.com" target="_blank">oliver.stasa@gmail.com</a></td></tr>
  </table>
  <h2>Základní metriky</h2>
  <p>Online program probíhal 21. — 25. 5. a 27. 5.</p>
  <canvas id="anal"></canvas>
  <br>
  <table class="desc">
    <tr><td>*</td><td>IMPRESE</td><td>Zobrazení jakéhokoliv obsahu spojeného s famufestem (výsledky uvedeny v 1/100)</td></tr>
    <tr><td>**</td><td>PŘEHRÁNÍ</td><td>Sledování 50%+ filmu</td></tr>
    <tr><td>***</td><td>NÁVŠTĚVNÍCI</td><td>Unikátní lidé</td></tr>
    <tr><td>****</td><td>NÁVŠTĚVY</td><td>Vstupy na web provedené i unikátními i vracejícími se návštěvníky</td></tr>
  </table>
  </div>
  <div class="blok hidden">
  <h2>Doba návštěvy</h2>
    Facebook <i>vs</i> přímý přístup & vyhledávání:
    <ul>
      <li>Facebook: 50% návštěvnosti, nízká retence*</li>
      <li>Direct+vyhledávání: 40% návštěvnosti, vysoká aktivita</li>
    </ul>
    <table class="desc">
      <tr><td>*</td><td>Nemůžu s jistotou určit pro kterou informaci tito diváci přišli, web je postaven jednoduše a tudíž návštěvy netrvající dlouho mohou být pochvalou, pravděpodobně se jedná o zjištění začátku programu o který mají zájem, nebo prohlédnutí informací o filmu, diváci také mohou používat vimeo-streaming obsahu na TV, což je po spuštění přehrávání odkáže ven z webu</td></tr>
    </table>
    <table>
      <tr><th>Doba návštěvy</th><th>Návštěv</th><th>Landing page</th><th>Zařízení, zdroj</th></tr>
      <tr><td>10s</td><td>17,799</td><td>≈80% film, program</td><td>telefon, facebook</td></tr>
      <tr><td>10s—3min</td><td>1,172</td><td>≈35% program, mix</td><td>mix</td></tr>
      <tr><td>3—30min</td><td>1,905</td><td>≈70% přehrávání filmů</td><td>počítač, direct</td></tr>
      <tr><td>30min+</td><td>402</td><td>≈přehrávání filmů</td><td>počítač, direct</td></tr>
    </table>
  </div>
  <div class="blok hidden">
  <h2>Čas návštěv</h2>
    <ul>
      <li>Vrchol návštěvnosti* zpravidla <span>20.00—22.00</span></li>
    </ul>
    <table class="desc">
      <tr><td>*</td><td>Vrchol návštěvnosti je o 1/3 vyšší než po zbytek dne (den = 8:00—23:59)</td></tr>
    </table>
  </div>
  <div class="blok hidden">
  <h2>Zdroj návštěvy</h2>
  <canvas id="zdroj"></canvas>
  <br>
  </div>
  <div class="blok hidden">
  <h2>Tok uživatelů webem</h2>
    <ul>
      <li>Většina uživatelů se zastavila na hlavní stránce a programu</li>
      <li>Celkem <span>29,617</span> návštěv jednotlivých stránek</li>
      <li>6,235 přístupů přišlo na <a href="PREHRAT.pdf" target="_blank">stránku pro přehrání filmů</a> a z nich 90% pokračovalo k prohlížení jednotlivých filmů ve videotéce</li>
      <li>Největší sledovanost měl film <i>Nebát se ničeho</i></li>
      <li>Mapa toku uživatelů <a href="./ANALYTICS_TOK.pdf" target="_blank">.PDF</a> (zjednodušená)</li>
    </ul>
  </div>
  <div class="blok hidden">
  <h2>Mapa</h2>
    <ul>
      <li>Web navštívili uživatelé z 63 zemí</li>
      <li>Mapa přístupů na web <a href="MAPA.pdf" target="_blank">.PDF</a></li>
      <li>Mapa přístupů k videím <a href="stats_export_map.csv" target="_blank">.CSV</a></li>
    </ul>
    <table>
      <tr><th>Země</th><th>Podíl</th></tr>
      <tr><td>ČR</td><td>87.2%</td></tr>
      <tr><td>USA</td><td>4.2%</td></tr>
      <tr><td>SK</td><td>2.4%</td></tr>
      <tr><td>Německo</td><td>0.8%</td></tr>
      <tr><td>Anglie</td><td>0.7%</td></tr>
      <tr><td>Polsko</td><td>0.4%</td></tr>
    </table>
  </div>
  <div class="blok hidden">
    <h1>Tvorba webu</h1>
    <ul>
      <li>Web má vlastní redakční systém <span>nezávislý na externích modulech</span></li>
      <li>Umožňuje správu takřka všech stěžejních částí webu</li>
      <li>Plně přístupný skrze mobilní verzi, responzivní bez ztráty obsahu</li>
      <li>Lze jej využívat jako <span>online knihovnu filmů</span> a k live-streamům</li>
      <li>Archivuje veškerý obsah pro každý ročník</li>
      <li>Studentský festival v online prostředí je zatím rarita, ukázal se ale jako funkční, tudíž se nabízí prostor posunout web dál — během roku by mohl sloužit jako VOD platforma pro zpoplatněné přehrávání studentských filmů*</li>
    </ul>
    <table class="desc">
      <tr><td>*</td><td>Z technického hlediska to znamená přidat <i>profilový</i> a <i>platební modul</i>, jinak je web ve všech ohledech připraven</td></tr>
    </table>
    <br>
  </div>
  <div class="blok hidden">
    <table>
      <tr><td><a href="https://web.dev/vitals/" target="_blank">Core Web Vitals</a></td><td>V období 30 dní konání festivalu (1. — 30. 5. 2020) všechny stránky tohoto webu <span style="color: green;">prošly</span>.</td></tr>
      <tr><td><a href="https://developers.google.com/speed/pagespeed/insights/?hl=cs&url=https%3A%2F%2Fwww.famufest.cz%2Fprogramme%2Fday%2Fall&tab=desktop" target="_blank">PageSpeed&nbsp;Insights</a></td><td>93%</td></tr>
      <tr><td>Velikost webu v prohlížeči</td><td>237kb</td></tr>
      <tr><td>Počet znaků kódu</td><td>478,458</td></tr>
      <tr><td>Easter eggy</td><td>3</td></tr>
    </table>
    <br>
  </div>
  <div class="blok hidden">
    <table>
      <tr><th>Druh práce</th><th>Strávený čas</th></tr>
      <tr><td>Vývoj</td><td>446h 31min</td></tr>
      <tr><td>Testování</td><td>≈35h</td>
      <tr><td>Plánování</td><td>≈40h</td>
    </table>
    <br>
    <table>
      <tr><th>Vrstva</th><th>Technologie</th></tr>
      <tr><td>Frontend</td><td>JS ES6<br>jQ 3.5<br>AJAX</td></tr>
      <tr><td>Backend</td><td>PHP 7.3</td></tr>
      <tr><td>Database</td><td>MySQL 5.6</td></tr>
      <tr><td>Coded in</td><td>HTML 5</td></tr>
      <tr><td>Styled in</td><td>CSS 3</td></tr>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
var ctx = document.getElementById('anal').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',

    data: {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
        datasets: [{
            label: 'FACEBOOK IMPRESE n/100*',
            backgroundColor: 'rgba(66,103,178, 0.3)',
            borderColor: 'rgb(66,103,178)',
            data: [32/100, 27/100, 61/100, 20/100, 39/100, 45/100, 111/100, 3202/100, 293/100, 6826/100, 7136/100, 16313/100, 6817/100, 13047/100, 52083/100, 75255/100, 69635/100, 86135/100, 74679/100, 91989/100, 71641/100, 119994/100, 101768/100, 142351/100, 152100/100, 115228/100, 27194/100, 43911/100, 6142/100, 2674/100, 1542/100]
        }, {
            label: 'VIMEO PŘEHRÁNÍ**',
            backgroundColor: 'rgba(25, 183, 234, 0.3)',
            borderColor: 'rgb(25, 183, 234)',
            data: [120, 204, 277, 333, 337, 200, 145, 163, 116, 221, 164, 261, 187, 193, 145, 151, 128, 116, 143, 284, 1982, 1502, 1858, 2437, 1904, 170, 1578, 63, 97, 76]
        }, {
          label: 'VIMEO NÁVŠTĚVNÍCI*',
            backgroundColor: 'rgba(25, 183, 234, 0.3)',
            borderColor: 'rgb(25, 183, 234)',
            data: [33, 65, 125, 129, 117, 87, 73, 57, 47, 73, 68, 80, 89, 86, 57, 64, 62, 54, 54, 69, 529, 459, 634, 718, 554, 78, 507, 32, 32, 29]
        }, {
            label: 'WEB NÁVŠTĚVY**',
            backgroundColor: 'rgba(255, 0, 0, 0.3)',
            borderColor: 'rgb(255, 0, 0)',
            data: [7, 11, 11, 22, 20, 14, 74, 68, 56, 67, 157, 89, 151, 332, 261, 200, 198, 320, 493, 947, 1710, 1688, 2010, 2136, 1640, 682, 1148, 466, 149, 102, 99]
        }
      ]
    },

    options: {responsive: true}
});
var ctx = document.getElementById('zdroj').getContext('2d');
var chart = new Chart(ctx, {
    type: 'doughnut',

    data: {
        datasets: [{
					data: [
            5554, 2413, 2006, 676
					],
					backgroundColor: [
            "rgb(66,103,178)",
            "pink",
            "red",
            "gray"
					],
					label: 'Zdroje návštěvnosti'
				}],
        labels: ["Facebook", "Přímá adresa", "Google vyhledávač", "Ostatní"]
    },

    options: {
					responsive: true,
					legend: {
						position: "left",
					}}
});
Chart.defaults.global.defaultFontColor = 'black';
Chart.defaults.global.defaultFontFamily = 'regular';

/*

<div class="blok hidden">
  <div id="banner"><div>FF</div></div>
</div>
#banner {width: 100%; height: 30vh; background: red; font-size: 28vh;}
#banner div {margin-top: -6vh; margin-left: 3vh; position: absolute; color: white;}



<div id="oy"></div>
#oy {width: 100vw; height: 50vh; background: url('web.jpg') no-repeat center top; background-size: contain; background-attachment: fixed;}

*/
</script>
</body>
</html>
