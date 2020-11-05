// ODKAZY
// pozn. chtelo by to udelat <a href> rebuild pomoci js, je to prijemnejsi pro vyhledavace videt to v html, popr. udelat sitemapu
////////////////////////////////////////////////////////////////////////////////
$(document).on('touchstart', function(){});
$(document).on('click touch', '#menu li, .link, .film, .news', function() {

    if ($('#hamburgr').is(':visible')) {$('#menu').slideUp();}
    /*
    if ($(this).hasClass('go_read')) {
      window.open('https://showcase.dropbox.com/doc/FAMUFEST-36-CITARNA--A0L0jdhTGmu0w_qVjdVdJ0A7AQ-9l4gC3mAR4u1AqEwrgCd0', '_blank');
    } else if ($(this).hasClass('go_gallery')) {
      window.open('http://kittychaosplanet.org/AffectionDetour/', '_blank');
    } else if ($(this).hasClass('vstupenky')) {
      window.open('https://goout.net/cs/festivaly/famufest-36/vhbye/+kmqmo/', '_blank');
    }
    */

  var url = $(this).attr('link');
  if (url && url.length && url != "#") {
    page(url);
  } else {
    // pro pripad archivu
    var url_rok = $(this).attr('rok'),
        rocnik = $('#ra'+url_rok);
    if (url_rok && url_rok.length) {
      if (rocnik.is(':hidden')) {
        $('.archivni_rocnik:not(#ra+'+url_rok+')').slideUp();
        rocnik.slideDown();
      } else {
        rocnik.slideUp();
      }

    }
  }

});

// GO HOME Z KLIKU NA NAZEV FESTIVALU, DECHBEROUCI JA VIM
////////////////////////////////////////////////////////////////////////////////
$(document).on('click touch', '#ff', function() {

  page('home');

});

// HAMBURGR
////////////////////////////////////////////////////////////////////////////////
$(document).on('click touch', '#hamburgr', function(e) {

  if ($('#menu').is(':visible')) {
        $('#menu').fadeOut();
  } else {
      // $(window).scrollTop(0); // $('html').animate({scrollTop: 0}, 250); // <= s animaci
      $('#menu').removeClass('page');
        $('#menu').fadeIn();
  }

});

// SHOW/HIDE MENU (pro KINO)
////////////////////////////////////////////////////////////////////////////////
/*
$(document).on('click touch', '#menubacker', function(e) {

    $('#menubacker').addClass('hidden');
    $('#menu').removeClass('hidden');

});
*/

// HOMEPAGE TOGGLE ELEM VISIBILITY
////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '#homepage', function() {
    if ($(window).width()/$(window).height() >= 1.5) {
      $('#menu, #novinka, #cookie').toggleClass('hidden');
      var video = $('#homepage video');
      if (video.prop('muted') === false) {
        video.prop('muted', true);
      } else {
        video.prop('muted', false);
      }
    }
});

// WINDOW ON RESIZE (HAMBURGR)
////////////////////////////////////////////////////////////////////////////////
$(window).on('resize', function(){
  if ($('#hamburgr').css('display') == 'none') {
    // ochrana proti zvetseni po zavreni burgeru
    if (window.location.pathname != '/') {
      $('#menu').addClass('page');
    }
    $('#menu').css({"display": "block"});

  } else {
    if ($(window).width()/$(window).height() >= 1.5) {
      $('#menu').slideUp();
    }
  }
});

// HLIDAC AKTIVITY, KDYZTAK RELOAD = KVULI SESSION A TAK
////////////////////////////////////////////////////////////////////////////////
$time = new Date().getTime();
$reload_time = 10*60*1000;
//$reload_time = 5*1000;
$refresher = setTimeout(function(){reload_page(false);}, $reload_time);

$(window).bind("keypress mousemove touch click", function() {
  $time = new Date().getTime();
});

$(window).bind("focus", function() {
  reload_page('force');
});

$(window).bind("blur", function() {
  clearTimeout($refresher);
  $time = new Date().getTime();
});

function reload_page(go) {
  console.log("sessions checked");
  if (new Date().getTime() - $time >= $reload_time*1.5 || go == 'force') {
    var path = window.location.pathname;
    var url = path.split('/');
    if (url[1] != null) {var str = url[1];} else {var str = 0;}
    if (url[2] != null) {var str2 = url[2];} else {var str2 = 0;}

      var daytime = $('head').attr('daytime');
      var kino = $('head').attr('kino');
      var filmoteka = $('head').attr('filmoteka');
      $.get('/php/sessions_load.php?tester='+daytime+'|'+kino+'|'+filmoteka, function(data) {
        if (data == 'reload') {

          if (str == 'admin') {
            if (!$('#reloader').length) {
              $('#menu').prepend('<li link="/reload" id="reloader">NAHRÁT ZMĚNU ↺</li>');
            }
          } else if (str != 'admin' || str != 'live' || (str != 'films' && str2 != 'film')) {
            window.location.reload();
          }

        }
      });

  } else {
    $refresher = setTimeout(reload_page, $reload_time);
  }
}

// HOVER nad (A) a (S) u filmovych kategorii
////////////////////////////////////////////////////////////////////////////////
$(document).on({
  'mouseenter': function() {

  var tl = $(this);
  switch (tl.attr('class')) {
    case 'aramis': var text = jazyk('ARAMISOVA CENA', 'ARAMIS PRICE'); break;
    case 'soutez_s': case 'soutez_c': var text = jazyk('SOUTĚŽNÍ BLOK', 'COMPETITION BLOCK'); break;
    case 'embed': var text = jazyk('FILM K PŘEHRÁNÍ', 'AVAILABLE TO WATCH'); break;
  }

    var fromtop = tl.offset().top+tl.height()/2;
    var fromleft = tl.offset().left;
    if (!$('.titulek').length) {
      $('body').append('<div class="titulek">'+text+'</div>');
    } else {
      $('.titulek').html(text);
    }
    $('.titulek').css({'top': 'calc('+fromtop+'px + 1.8vh)', 'left': fromleft+'px'});
    $('.titulek').stop(true,false).show();

  },
  'mouseleave click touch': function() {

    $('.titulek').remove();

  }
}, '.aramis, .soutez_c, .soutez_s, .embed');

// VRATKA JAZYKU
////////////////////////////////////////////////////////////////////////////////
function jazyk(cz, en) {

  switch($('li[link="/lang"]').html()) {
    case 'CZ': return en; break;
    default: case 'EN': return cz; break;
  }

}

// EASTER EGGS
////////////////////////////////////////////////////////////////////////////////
$('head').append('<script type="text/javascript" src="/js/easter.js"></script>');

// cookies a analytics
////////////////////////////////////////////////////////////////////////////////
$.get('/php/document/cookies.php?uri='+window.location.pathname, function(data) {
  $('head').append(data);
});

$.get('/php/document/analytics.php', function(data) {
  $('head').append(data);
});

// click on play button u venue
////////////////////////////////////////////////////////////////////////////////
$(document).on('click touch', 'h1 .toplay', function() {

    page('/live');

});

// LOADING NAHORE LISTA
////////////////////////////////////////////////////////////////////////////////
function loading(e) {
  switch (e) {
    case 'on':
      if (!$('#loading').length) {
        $('body').prepend('<div id="loading"></div>');
          $('#loading').stop(true, false).fadeIn(100);
      }
    break;
    case 'off':
      $('#loading').stop(true, false).fadeOut(500,
      function() {
        $('#loading').remove();
      });
    break;
  }
}

// BG PRELOAD
////////////////////////////////////////////////////////////////////////////////
function bg_preload(imgs){
  var urls = imgs.split('|');
  for (var i = 0; i < urls.length; i++) {
     var bg = new Image();
     bg.src = urls[i];
  }
  return true;
}

// PREPINAC STRANEK
////////////////////////////////////////////////////////////////////////////////
function page(url) {

  var stranka;
  var adress = url;
  var path = url.split('/');
  url = path[1];

  if ($('.titulek').length) {
    $('.titulek').remove();
  }

  switch (url) {
    /////////////
    default: case 'home': // homepage
      stranka = 'home';
      var file = '/php/page/homepage.php';
      if ($(window).width()/$(window).height() < 1.5) {
        $('#menu').slideDown();
      }
    break;
    /////////////
    case 'lang': // zmena jazyku
      stranka = url;
      var file = '/php/page/filmy.php';
    break;
    /////////////
    case 'reload':
      window.location.reload();
      break;
    break;
    /////////////
    case 'programme':
      stranka = url;
      if (path[3] != null) {
        switch (path[2]) {
          case 'day':
            var file = '/php/page/program_day.php?d='+path[3];
            url = 'programme/day/'+path[3];
          break;
          case 'block':
            var file = '/php/page/program_block.php?b='+path[3];
            url = 'programme/block/'+path[3];
          break;
          case 'event':
            var file = '/php/page/program_event.php?e='+path[3];
            url = 'programme/event/'+path[3];
          break;
          case 'venue':
            var file = '/php/page/program_venue.php?v='+path[3];
            url = 'programme/venue/'+path[3];
          break;
        }
      }
    break;
    /////////////
    case 'live':
      stranka = url;
      if (path[3] != null) {
        switch (path[2]) {
          case 'chat':
            var file = '/php/chat_name.php?hash='+path[3];
            url = 'live/chat/'+path[3];
          break;
        }
      } else {
        var file = '/php/page/cinema.php';
        url = 'live';
      }
    break;
    // v pripade rozsireni na dalsi rocniky je potreba tady dodelat stejne jako v programu prepinac misto dni se budou prepinat roky
    /////////////
    case 'films':
      stranka = url;
      if (path[3] != null) {
        switch (path[2]) {
          case 'category':
            var file = '/php/page/films_show.php?c='+path[3];
            url = 'films/category/'+path[3];
          break;
          case 'film':
            var file = '/php/page/films_film.php?f='+path[3];
            url = 'films/film/'+path[3];
          break;
          default:
            var file = '/php/page/films.php?f='+path[2];
            url = 'films/'+path[2];
          break;
        }
      } else {
        var file = '/php/page/films.php';
      }
    break;
    /////////////
    case 'news':
      if (path[2] != null) {
        var file = '/php/page/news.php?n='+path[2];
        url = 'news/'+path[2];
      } else {
        var file = '/php/page/news.php';
      }
    break;
    /////////////
    case 'visitors':
      var file = '/php/page/visitors.php';
    break;
    /////////////
    case 'gallery':
      var file = '/php/page/galery.php';
    break;
    /////////////
    case 'about':
      var file = '/php/page/about.php';
    break;
    /////////////
    case 'places':
      var file = '/php/page/places.php';
    break;
    /////////////
    case 'partners':
      var file = '/php/page/partners.php';
    break;
    /////////////
    case 'contact':
      var file = '/php/page/contact.php';
    break;
    /////////////
    case 'archive':
      if (path[2] != null) {
        $.post('/php/changeYear.php', {'rok': path[2]}, function(res){
          if (res == 'ok') {
            window.location.reload();
          }
        });
        break; break;
      } else {
        var file = '/php/page/archive.php';
      }
    break;
    /////////////
    case 'admin':
        if (adress == '/admin/logout') {
            $.get('/php/admin/logout.php', function(res) {
              if (res == 'loggedout') {
                $("#menu_admin").remove();
                page('home');
              } else {
                alert('ERROR');
              }
            });
        } else {
          var file = '/php/admin/index.php?pg='+adress;
          url = adress.substring(1);
        }
    break;
  }

  // ZMENA VELIKOSTI MENU kdyby menubacker
  /*
  if ($('#menu').hasClass('hidden')) {
    $('#menu').removeClass('hidden');
    $('#menubacker').addClass('hidden');
  }
  */

  if (!stranka || stranka != 'home' && stranka != 'lang') {

    // POKUD MENU NENI ZMENSENY, ZMENSIT
    // && !$('#hamburgr').is(':visible')

    if (!$('#menu').hasClass('page')) {
      $('#menu, #ff').addClass('page');
      if (!$('#novinka').hasClass('hidden')) {
        $('#novinka').addClass('hidden');
      }
      if (!$('#cookie').hasClass('page')) {
        $('#cookie').addClass('page');
      }
    }

    // menu backer
    /*
    if (stranka == 'live') {

      $('#menu').addClass('hidden');
      $('#menubacker').removeClass('hidden');

    }
    */

  } else if (stranka == 'home') {

    // POKUD MENU NENI ZVETSENY, ZVETSIT
    if ($('#menu').hasClass('page')) {
      $('#menu, #ff').removeClass('page');
      $('#novinka').removeClass('hidden');
      $('#cookie').removeClass('page');
    }
    var pockat = 0;

  }

  ///////////////////////////////////////
  // START LOADING PAGE
  ///////////////////////////////////////
  loading('on');

  // POKUD JE UVNITR STRANKY #CONTENT/#HOMEPAGE, UDELAT FADEOUT
  if (($('#content').length && path.length == 2) || ($('#homepage').length && stranka != 'home')) {
    if ($('#content').length) {
      $('#content').addClass('fadeout');
      var div = $('#content');
    } else {
      $('#homepage').addClass('fadeout');
      var div = $('#homepage');
    }
      // z nejakeho duvodu nefunguje vytazeni z css
      var dur = div.css('transition-duration');
      var pockat = 	(dur.substring(0, dur.length-1))*1000;
  }

  // pojisteni proti bg preloadu u eventu
  if ($('.fullscreen_thumb').length) {
    $('.fullscreen_thumb').addClass('fadeout');
    setTimeout(function(){
      $('.fullscreen_thumb').remove();
    }, 250);
    pockat = pockat*2;
  }

  // JAKMILE SE DOKONCI FADEOUT, SPUSTIT LOAD OBSAHU
  setTimeout(function(){

    switch (stranka) {
      /////////////
      case 'home':
        if (!$('#homepage').length) {
          title('');
          $("#pg").load(file, function(){
              $.get('/php/bg_preload.php', function(imgs){
                if (imgs) { // TO PHP VRACI URL JEN PRO IMG
                  // PRELOAD PRO IMG
                  if (bg_preload(imgs) && $('#homepage').ready()) {
                    $('#homepage').removeClass('fadeout');
                  }
                // POKUD NIC NENI IMG (= JE TO VIDEO), SRAT NA PRELOAD
                } else {
                  $('#homepage').removeClass('fadeout');
                }
              });
          loading('off');
          });
        } else {
          loading('off');
        }
      break;
      /////////////
      case 'lang':
        $.get('/php/lang_switch.php', function(){
          window.location.reload();
          loading('off');
        });
      break;
      /////////////
      case 'programme': case 'films':
        if (((!$("#content").length && path.length >= 2) || ($("#content").length && path.length == 2) || !$(".content").length) || !$("#content").hasClass(stranka)) {
            if (!$("#content").hasClass(stranka)) {
              $("#content").addClass('fadeout');
              $("#content").remove();
            }
            $("#pg").html('<div id="content" class="fadeout '+stranka+'"></div>');
            switch (stranka) {
              case 'programme': var def_file = '/php/page/program.php?p='+path[3]; var def_con = '/php/page/program_day.php'; break;
              case 'films': var def_file = '/php/page/films.php'; var def_con = '/php/page/films_show.php'; break;
            }
            $("#content").load(def_file, function(){
              // DEFAULT OBSAH
              if ($("#content").length && path.length == 2) {
                $('.content').load(def_con, function(){
                    $('#content').removeClass('fadeout');
                    loading('off');
                });
              } else {
                $('#content').removeClass('fadeout');
                loading('off');
              }
            });
        }
        var contentchecker = setInterval(function(){
        if (path.length > 2 && $('.content').length) {
          clearInterval(contentchecker);
          $(".content").addClass('fadeout');
            var dur = $('#content').css('transition-duration');
            var pockat = dur.substring(0, dur.length-1)*1000;
            setTimeout(function(){
              $(".content").load(file, function(){
                if ((path[2] == 'film' || path[2] == 'event') && path[3] != null) {
                  $('body').append('<div class="fullscreen_thumb fadeout"></div>');
                  $.get('/php/bg_preload.php?db=event&link='+path[3], function(imgs){
                    if (imgs) {
                      if (bg_preload(imgs) && $('.fullscreen_thumb').ready()) {
                        $('.fullscreen_thumb').removeClass('fadeout');
                      }
                    } else {
                      $('.fullscreen_thumb').removeClass('fadeout');
                    }
                  });
                }
                $('.content').removeClass('fadeout');
                loading('off');
              });
            }, pockat);
        }
      }, 100);
        setTimeout(function(){
          title(url);
        }, pockat);
      break;
      /////////////
      default:
        title(url);
        $("#pg").html('<div id="content" class="fadeout"></div>');
        $("#content").load(file, function(){
          $('#content').removeClass('fadeout');
            // kdyby nacital BG k novince
            if (path[1] == 'news' && path[2] != null) {
              $('body').append('<div class="fullscreen_thumb fadeout"></div>');
              $.get('/php/bg_preload.php?db=news&link='+path[2], function(imgs){
                if (imgs) {
                  if (bg_preload(imgs) && $('.fullscreen_thumb').ready()) {
                      $('.fullscreen_thumb').removeClass('fadeout');
                  }
                } else {
                  $('.fullscreen_thumb').removeClass('fadeout');
                }
              });
            }
            //
          loading('off');
        });
      break;
    }

  }, pockat);

  ///////////////////////////////////////
  // END LOADING PAGE
  ///////////////////////////////////////

}

// URL A TITULEK STRANKY
////////////////////////////////////////////////////////////////////////////////
function title(new_url) {

    var title = new_url;
    // OZNACENI V MENU
    if (new_url) {
      var adr = '/'+new_url;
      var pos = adr.indexOf("/", 1);
        if (pos != -1) {
          // OZNACIT V CONTENTu
            $('.link').removeClass('selected');
            if ($('.link[link="'+adr+'"]').length) {
              $('.link[link="'+adr+'"]').addClass('selected');
            }
            adr = adr.substring(0, pos);
        }
        if (adr == '/programme') {
          adr = $('#prg').attr('link');
        } else if (adr == '/films') {
          adr = $('#flm').attr('link');
        }
        $('#menu li').removeClass('selected');
        $('#menu li[link="'+adr+'"]').addClass('selected');
      new_url = location.protocol+'//'+location.host+'/'+new_url;
    } else {
      $('#menu li').removeClass('selected');
      new_url = location.protocol+'//'+location.host;
    }

  // TITLE A URL
  $.get('/php/title.php?t='+title, function(title){
      document.title = title;
      window.history.pushState("object or string", title, new_url);
  });

  // OG:IMAGE
  /*
  $.get('/php/og_image.php?url='+title, function(og_image){
    if (!og_image) {
      og_image = '/data/ff36.png';
    } else {
      og_image = og_image;
    }
    $('meta[property=og\\:image]').attr('content', og_image);
  });
  */

}

// ZKOPIROVAT NOVINKU ABY JELA HEZKY V TOM SLAJDU
////////////////////////////////////////////////////////////////////////////////
function setup_intro_news() {
  var text = $('.slider div').html();
  var metr = Math.round(120/text.length);
  var vysledek = '';
  for (t = 0; t <= metr; t++) {
    vysledek = text+'&emsp;'+vysledek;
  }
  $('.slider').html('<div>'+vysledek+'</div><div>'+vysledek+'</div>');
  $('#novinka').addClass('edited');
}

// NAHRANI OBSAHU PODLE TOHO JAKA JE ADRESA
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {

      var url = window.location.pathname;
      page(url);

      if ($('#novinka').length && !$('#novinka').hasClass('edited')) {
        setup_intro_news();
      }

      if ($(window).width()/$(window).height() >= 1.5){
        $('#homepage video').removeAttr('autoplay');
      } else {
        $('#homepage video').attr('autoplay');
      }

});

// KDYZ NEJAKEJ BOOMER ZMACKNE "ZPET"
////////////////////////////////////////////////////////////////////////////////
$(window).on('popstate', function(e){

    //setTimeout(function(){
      var url = window.location.pathname;
      page(url);
      e.preventDefault();
    //}, 500);

});
