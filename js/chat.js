// KEEP VEIWPORT COOL ON MOBILE
////////////////////////////////////////////////////////////////////////////////
setTimeout(function () {
        let viewheight = $(window).height();
        let viewwidth = $(window).width();
        let viewport = document.querySelector("meta[name=viewport]");
        viewport.setAttribute("content", "height=" + viewheight + "px, width=" + viewwidth + "px, initial-scale=1.0");
}, 300);

// MAINTAIN CHAT HEIGHT
////////////////////////////////////////////////////////////////////////////////
$(window).on('resize', function(){
  setTimeout(function(){
    maintainchatheight();
  }, 100);
});
function maintainchatheight(){
  if ($(window).width()/$(window).height() > 1.5) {
    var newheight = $(".video").height()-$("#msg form").height();
    var videosize = $(".video").height();
    $('#chatheight').html('<style>.chat::before{padding-top: calc('+newheight+'px);} #streamidle{width: calc(100% - '+(videosize/28*1.6*2)+'px); margin-top: -'+(videosize/28*2.7)+'px; padding: 0 '+(videosize/28*1.6)+'px; font-size: '+(videosize/28)+'px;}</style>');
  } else {
    var newheight = $(window).height()-$(".video").height();
    $('#chatheight').html('<style>.chat::before{padding-top: calc('+newheight+'px - 24vh);} #feed {height: calc('+newheight+'px - 24vh - 1vh) !important;}</style>');
  }
}

// CHECK FOR NEW MESSAGES
////////////////////////////////////////////////////////////////////////////////
function check(timer) {
  clearTimeout(window.load_msg);
  window.load_msg = setTimeout(function(){

    if ($('.msg')[0]) {
      var posledni = $('.msg').last().attr('num');
    } else {
      var posledni = 0;
    }

    $.post('/php/chat.php?action=show', {lastid: posledni}, function(res) {
      switch (res) {
        case 'wrong': alert(jazyk('chyba v přijatých datech', 'error in recived data')); break;
        default:
          var delka = res.length;
          if (delka) {

            $('.info').remove();
            setTimeout(function(){

              //clearTimeout(window.load_msg);

              var kdejebar = $('#feed').scrollTop();
              var vyskabar = $('#feed').prop('scrollHeight')-$('#feed').outerHeight(); // -$('.msg').last().height()+5; // 5 tj tolerance pro jistotu
              if (kdejebar < vyskabar) {
                $('#feed').animate({scrollTop: vyskabar}, 250);
              }

              check(1000);

            }, 250);

            $('#feed').append('<div class="newmsg">'+res+'</div>');

          } else {

              if (!$('#feed .info').length && posledni == 0) {
                  $('#feed').append('<div class="info"><center>'+jazyk('Podělte se s dalšími diváky o svůj zážitek', 'Share your experience with other viewers')+'</center></div>');
              }

              if (timer < 5000) {timer = timer+250;}
              check(timer);

          }
        break;
      }
    });
  }, timer);
}

// SEND MESSAGE
////////////////////////////////////////////////////////////////////////////////
$('#msg form').submit(function() {

    $('.info').remove();
    var val = $('#msg input[type="text"]').val();
    if (val.length) {

      val = val.replace(/,/g, "/carka/");
      val = val.replace(/"/g, "/uvozovka/");
      val = val.replace(/'/g, "/uvozovka2/");
      val = val.replace(/:/g, "/dvojtecka/");
      val = val.replace(/;/g, "/strednik/");
      val = val.replace(/<3/g, "/srdce/");

      $.post('/php/chat.php?action=add', {name: $.cookie("jmeno"), msg: val}, function(res) {
        switch (res) {
          case 'ok':
            $('#msg input[type="text"]').val('');
            clearTimeout(window.load_msg);
            check(250);
          break;
          case 'wrong': alert(jazyk('chyba v přijatých datech', 'error in recived data')); break;
          case 'empty': alert(jazyk('prázdná zpráva', 'empty message')); break;
          default: alert('ERROR\n'+res); break;
        }
      });

    }

    return false; // deaktivuje odeslani formu

});


// WRITE MESSAGE = CHECK FOR COOKIE OF NAME
////////////////////////////////////////////////////////////////////////////////
$(document).on('focus', '#msg input[type="text"]', function(){

  var nam = $.cookie("jmeno");
  if (nam == null || nam == 'null') {
    $('#msg').hide();
    $('#enter_name').show().addClass('wiggle');
      $('#enter_name input[type="text"]').focus();
  } else {
    // append_namechanger();
  }

});

// WRITE MESSAGE = ON BLUR
////////////////////////////////////////////////////////////////////////////////
$(document).on('blur', '#msg input[type="text"]', function(){
  setTimeout(function(){
    $('.info').slideUp(500, function(){
      $('.info').remove();
    });
  }, 500);
});

// WRITE MESSAGE = ON TYPING
////////////////////////////////////////////////////////////////////////////////
$(document).on('keypress', '#msg input[type="text"]', function(){
  append_namechanger();
});

// NAME CHANGER
////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.namechanger', function(){

  $.cookie("jmeno", null);

  $('.info').remove();
  $('#enter_name input[type="text"]').val('');
  $('#enter_name input[type="text"]').addClass('taken');
  $('#msg input[type="text"]').focus();

});

function append_namechanger() {

  setTimeout(function(){
    $('html').animate({scrollTop: $('html').outerHeight()}, 500);
  }, 500);
  $('.info').remove();
  var jmeno = $.cookie("jmeno");
  jmeno = jmeno.replace("(s)", "");
  jmeno = jmeno.replace("(/s)", "");
  $('#feed').append('<div class="info"><span class="namechanger">'+jmeno+'</span> '+jazyk('píše...', 'is writing...')+'</div>');
  $('#feed').scrollTop($('#feed').prop('scrollHeight')-$('#feed').outerHeight());

}

// WRITE NAME = TESTER
////////////////////////////////////////////////////////////////////////////////
$(document).on('change keyup paste input[type="text"]', '#enter_name input[type="text"]', function(){

  $('#enter_name input[type="text"]').val($('#enter_name input[type="text"]').val().replace(/[&\/\\#,+()$~%.'":*?!<>{}]/g, ''));
  var jmeno = $('#enter_name input[type="text"]').val();
  if (jmeno.length < 3) {
    if (!$('#enter_name input[type="text"]').hasClass('taken')) {
      $('#enter_name input[type="text"]').addClass('taken');
    }
  } else {
    $.post('/php/chat.php?action=checkname', {name: jmeno}, function(res) {
      switch (res) {
        case 'taken':
          if (!$('#enter_name input[type="text"]').hasClass('taken')) {
            $('#enter_name input[type="text"]').addClass('taken');
          }
        break;
        default:
          if ($('#enter_name input[type="text"]').hasClass('taken')) {
            $('#enter_name input[type="text"]').removeClass('taken');
          }
        break;
        //case 'wrong': alert(jazyk('chyba v přijatých datech', 'error in recived data')); break;
      }
    });
  }

});

// SUBMIT NEW NAME
////////////////////////////////////////////////////////////////////////////////
$("#enter_name form").submit(function(){

  var jmeno = $('#enter_name input[type="text"]').val();
  $.post('/php/chat.php?action=checkname', {name: jmeno}, function(res) {
    if (jmeno.length && res != 'taken') {

      $.cookie("jmeno", jmeno, { path: '/', expires: 999 });
      //$('#enter_name input[type="text"]').val($('#enter_name input[type="text"]').val()+' ✓');

          $('#enter_name').fadeOut(200);
            $('#msg').fadeIn(200);
              $('#msg input[type="text"]').focus();
              append_namechanger();

    } else {
      $("#enter_name").removeClass('wiggle');
        $("#enter_name").height();
      $("#enter_name").addClass('wiggle');
    }
  });

  return false; // deaktivuje odeslani formu

});

// ON CLICK LABEL => FOCUS INPUT
////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.label', function(){

  $('#enter_name input[type="text"]').focus();

});

// START DOCUMENT
////////////////////////////////////////////////////////////////////////////////
$(window).ready(function() {

  check(1000);
  setTimeout(function(){
    maintainchatheight();
  }, 100);

});


// $.cookie("jmeno", tojmeno, { path: '/', expires: 999 });


// ANIMOVANI
/*
if (delka < 1000) {
  var i = 0;
  $('.newmsg').last().find('.msg').each(function(){
    if (i < 200) {i = i+10;}
    $(this).delay(i).animate({opacity: 1}, 250);
  });
} else {
  $('.newmsg .msg').animate({opacity: 1}, 250);
}
*/
//$('.newmsg').last().animate({opacity: 1}, 250);
// /ANIMOVANI
