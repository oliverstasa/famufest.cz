// ZVETSENI TEXTU V "O FESTIVALU"
$(document).mousemove(function(e){

  if ($('.ofestu').length && $(window).width() > 1367 && e.pageX > $(window).width()/7) {
    var velikost = (e.pageX-$(window).width()/7)/150;
    $('.ofestu p').each(function(){
      $(this).css({'transform': 'translateX('+velikost+'vw)'});
      velikost = velikost*1.7;
    });
  } else {
    $('.ofestu p').css({'transform': 'translateX(0)'});
  }

});
