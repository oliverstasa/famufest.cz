$(document).ready(function(){

  $('body').prepend('<div id="score"><link href="/css/fighter.css" rel="stylesheet"><div id="protector"></div><audio autoplay loop><source src="/data/8bit.mp3" type="audio/mpeg"></audio></div>');
  var stranka = window.location.pathname;
  $.post("../../php/score.php", {addr: stranka}).done(function(data){
    $('#score').append('<div id="msg"><table id="scorelist"></table></div><div id="esc"><span>[ESC] to exit</span></div>');
    $('#scorelist').html(data);
    $('#msg').fadeIn();
  });

});

$(document).on('keydown', function (e) {

  if (e.keyCode == 27) {
    $('#score').remove();
    $('script[src="/js/score.js"]').remove();
  }

});
