$game_start = new Date().getTime();

var bomba = new Image();
bomba.src = '/data/img/boom.gif';

$(document).ready(function(){

  $('body').prepend('<div id="game"><link href="/css/fighter.css" rel="stylesheet"><div id="protector"></div><div id="fighter"></div><audio autoplay loop><source src="/data/8bit.mp3" type="audio/mpeg"></audio></div>');
  //$('body').css({position: 'fixed'});

});

$(document).on('mousemove', function(e){

  $('#fighter').css({left: (e.pageX-$('#fighter').width()/2)+'px'});

});

/*
$(document).on('keydown', function(e){

  if (e.keyCode == 37) { // left
    $('#fighter').css({left: "-=1vh"});
  } else if (e.keyCode == 39) { // right
    $('#fighter').css({left: "+=1vh"});
  }

});
*/

function deleteit(e){
  e.remove();
}

$targets = $('.film, .link, .news, li, #partners img, #visitors h1');
var t = $targets.length;
var hit = 0;
$pal = 0;

/*
targets.each(function(){
    var r1 = Math.floor(Math.random() * 10)+10/10;
    var r2 = Math.floor(Math.random() * 4)+4/4;
    var r3 = Math.floor(Math.random() * 10)+10/10;
    $(this).css({
     'animation': r1+'s esc infinite',
     'transition-delay': r2+'s',
     'animation-delay': r3+'s'});
});
*/

$("#game").on('click', function(e){

    e.preventDefault();
    $pal++;
    $('#game').append('<audio autoplay onended="deleteit(this);"><source src="/data/img/laser.mp3" type="audio/mpeg"></audio>');
    $('#game').append('<div class="ball"></div>');
    $('.ball:last-child').css({left: (e.pageX-$('.ball').width()/2)+'px', bottom: ($('#fighter').height()/2)+'px'}); // ($('#fighter').offset().left+$('#fighter').width()/2-$('.ball').width()/2)
    $('.ball:last-child').animate({bottom: "100vh"}, {
          duration: 1500,
          easing: 'linear',
          step: function(krok){
            var kule = $(this);
            var ball = new Object();
            /*
            ball.l = kule.offset().left;
            ball.t = kule.offset().top;
            ball.r = ball.l+kule.width();
            ball.b = ball.t+kule.height();
            */
            ball.ct = kule.offset().top+kule.height()/2;
            ball.cl = kule.offset().left+kule.width()/2;
            $targets.each(function(){
              var link = $(this);
              var elem = new Object();
              elem.l = link.offset().left;
              elem.t = link.offset().top;
              elem.r = elem.l+link.outerWidth();
              elem.b = elem.t+link.outerHeight();
                    if ((elem.l < ball.cl && elem.r > ball.cl) &&
                        (elem.t < ball.ct && elem.b > ball.ct) &&
                        !link.hasClass('trefen')) {
                          $('#game').append('<audio autoplay onended="deleteit(this);"><source src="/data/img/boom.mp3" type="audio/mpeg"></audio>');
                          $('#game').append('<div class="exploze" style="top: '+ball.ct+'px; left: '+ball.cl+'px"></div>');
                            $('.exploze:last-child').fadeOut(1000, function(){
                              $(this).remove();
                            });
                          kule.remove();
                          link.addClass('trefen');
                          hit++;
                          if (hit+1 == t) { // +1 proto ze je vzdy hidden .link novinky
                            var truecas = (new Date().getTime() - $game_start);
                            var cas = truecas/1000;
                            /*
                            page('/films');
                            setTimeout(function(){
                                $targets = $('.film, .link, .news, #partners img, #visitors h1');
                            }, 1500);
                            */
                            $('#game').append('<audio autoplay onended="deleteit(this);"><source src="/data/img/win.mp3" type="audio/mpeg"></audio>');
                            $('#game').append('<div id="hit" style="display: none;">'+hit+'</div><div id="cas" style="display: none;">'+truecas+'</div><div id="pal" style="display: none;">'+$pal+'</div><table id="msg"><tr><td valign="center"><span>CONGRATS, <input maxlength="6" type="text" style="width: 28vh; font-size: 6vh; background: #3700ff; color: white; padding: 1vh; border: none; outline: none; animation: fly 5s infinite; text-align: center;" id="name" placeholder="NAME"> DESTROYED FAMUFEST!</span><br><span>YOUR TIME: '+cas+'s AT <span id="addr">'+window.location.pathname+'</span></span><br><br><br><span>type your name and press [ENTER]</span></td></tr></table>');
                            $('#name').focus();
                            $('#msg').delay(1000).fadeIn(1000);
                          }
                    }
            });
    }, complete: function(){
      $(this).remove();
    }
    });

});

$send = 0;
$(document).on('keydown', function (e) {

  if (e.keyCode == 27) {
    $('.trefen').removeClass('trefen');
    $('#game').remove();
    $('script[src="/js/fighter.js"]').remove();
  }

  if (e.keyCode == 13 && $send == 0 && $('#name').is(':focus') && $('#name').val() != '') {
    $send = 1;
    if ($("#name").length) {
      var stranka = $('#addr').html();
      var cas = $('#cas').html();
      var jmeno = $("#name").val();
      var pal = $("#pal").html();
      var hits = $("#hit").html();
      $.post("../../php/fighter.php", {name: jmeno, cas: cas, addr: stranka, strel: pal, tref: hits}).done(function(data){
        if (data == 'done') {
          $('#game').append('<audio autoplay onended="deleteit(this);"><source src="/data/img/win.mp3" type="audio/mpeg"></audio>');
          $('#msg tr td').html('<span>NICE! type "score" to check leaderboards</span><br><span>[ESC] to exit</span>');
        } else {
          alert('ERROR => '+data);
        }
      });
    }
  }

});
