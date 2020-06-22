// LOGIN
////////////////////////////////////////////////////////////////////////////////
$('#login_form').submit(function() {

      var login_name = $('#login_name').val();
      var login_pass = $('#login_pass').val();

      $.post('/php/admin/login.php', {login: login_name, pass: login_pass}, function(res) {
        switch (res) {
          case 'logged': page('/admin'); break;
          case 'wrong': alert('NE'); break;
          default: alert('ERROR'); break;
        }
      });

      return false; // deaktivuje odeslani formu
});

// FORM HELPER
////////////////////////////////////////////////////////////////////////////////
$(document).on('keyup', 'input[name="zkr"], input[name="nazev_en"]', function(){
  if ($(this).attr('name') == 'zkr') {
    var input = $(this).val();
  } else {
    var input = $('input[name="nazev_en"]').val();
  }
  var input = input.replace(/\s+/g, '-').toLowerCase();
  var regExpr = /[^a-zA-Z0-9-.]/g;
  var input = input.replace(regExpr, "");
  switch ($('input[type="submit"]').attr('name')) {
    case 'blok':
      if ($(this).attr('name') == 'zkr') {
        $('input[name="link"]').val($('#rok_select').val()+'-'+input);
      }
    break;
    case 'news': case 'venue':
      $('input[name="link"]').val($('#rok_select').val()+'-'+input);
    break;
    default:
      $('input[name="link"]').val(input);
    break;
  }
});

// GENERATE HASH ON NAME KEYUP
////////////////////////////////////////////////////////////////////////////////
$(document).on('change keyup paste input', 'input[name="name"]', function(){

  var text = $('input[name="name"]').val();
  $.post('/php/md5.php', {txt: text}, function(res) {

     $('input[name="hash"]').val(res);

  });

});

// TEST FOR VALID STREAM LINK => ytb, fb, vimeo
////////////////////////////////////////////////////////////////////////////////
$load_stream = false;
$(document).on('keyup paste', 'input[name="stream_link"]', function(){

  clearTimeout($load_stream);
  $('#stream_tester').html('nahrávám...');

  var val = $('input[name="stream_link"]').val();
  val = val.replace(/,/g, "/carka/");
  val = val.replace(/"/g, "/uvozovka/");
  val = val.replace(/'/g, "/uvozovka2/");
  val = val.replace(/:/g, "/dvojtecka/");
  val = val.replace(/;/g, "/strednik/");
  $.post('/php/stream_linker.php', {txt: val}, function(res) {

       if (res.indexOf("|") != -1) {
         var vys = res.split('|');
         var vystup = vys[0];
         var obsah = vys[1];
       } else {
         var vystup = false;
         var obsah = 'náhled';
       }

       if (vystup == 'facebook' || vystup == 'youtube' || vystup == 'vimeo') {

         var ven = obsah;

       } else {

         if (res != '') {obsah = res;}
         var ven = obsah;

       }

       var $load_stream = setTimeout(function(){
         $('#stream_tester').html(ven);
       }, 1000);

  });

});

// HOVER ADMIN MENU
////////////////////////////////////////////////////////////////////////////////
$(document).on({
  'mouseenter': function() {

  var tl = $(this);
  switch (tl.html()) {
    case 'NOVINKY': var text = 'Novinka se začne zobrazovat na webu, pokud je nastaveno:<ul><li>{STAV}: [PUBLIKOVÁNO]</li><li>{OD}: [datum/čas < současnost]</li></ul>'; break;
    case 'EVENTY': var text = 'FILMY a UDÁLOSTI'; break;
    case 'KATEGORIE': var text = 'kat'; break;
    case 'BLOKY': var text = 'block'; break;
    case 'VENUES': var text = 'venues'; break;
    case 'PROGRAM': var text = 'prg'; break;
    case 'KINO': var text = 'kino'; break;
    case 'CHAT': var text = 'chat'; break;
    case 'ROČNÍK': var text = 'year'; break;
    case 'REŽIM': var text = 'sett'; break;
    case 'ODHLÁSIT SE': var text = ';)'; break;
  }

    var fromtop = tl.offset().top+tl.height()/2;
    if (!$('.titulek').length) {
      $('body').append('<div class="titulek" style="max-width: 35vh;">'+text+'</div>');
    } else {
      $('.titulek').html(text);
    }
    var fromleft = tl.offset().left+tl.outerWidth()/2-$('.titulek').outerWidth()/2;
    $('.titulek').css({'top': 'calc('+fromtop+'px + 4vh)', 'left': fromleft+'px'});
    $('.titulek').stop(true,false).show();

  },
  'mouseleave click touch': function() {

    $('.titulek').remove();

  }
}, 'nothing'); // #admin_menu .link

// COPY ODKAZ DO SCHRANKY
////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.copyme', function(){

  var div = $(this);
  var link = div.attr('copytext');
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(link).select();
  document.execCommand("copy");
  $temp.remove();

    div.addClass('wiggle ok');
    div.html('HOTOVO: CTRL+V ✓'); // ✓ —
      /*
      setTimeout(function(){
        div.removeClass('wiggle ok');
        div.html('ZKOPÍROVAT ODKAZ');
      }, 2000);
      */

});
$(document).on('mouseleave', '.copyme', function(){
  var div = $(this);
  div.removeClass('wiggle ok');
  div.html('ZKOPÍROVAT ODKAZ');
});

// CISTIC LINKU OD SPECIALNICH ZNAKU
////////////////////////////////////////////////////////////////////////////////
$(document).on('change keyup paste input', 'input[name="link"]', function(){
  var regExpr = /[^a-zA-Z0-9-. ]/g;
  var cisty = $(this).val().replace(regExpr, "");
  var cisty = cisty.split(' ').join('');
  $(this).val(cisty);
});

// TEXTAREA ZVETSOVAC
////////////////////////////////////////////////////////////////////////////////
/*
$(document).on('focus', 'textarea', function(){
  $(this).addClass('large');
});
$(document).on('blur', 'textarea', function(){
  $(this).removeClass('large');
});
*/

// ZMENIT ROCNIK
////////////////////////////////////////////////////////////////////////////////
$("#rok_select").change(function(){
  switch ($(this).val()) {
    case 'add':
      page('/admin/rok/show');
    break;
    default:
      $.get('/php/admin/rok_switch.php?to='+$(this).val(), function(res){
        page($("#rok_select").attr('from'));
      });
    break;
  }
});

// THUMB UPLOAD
////////////////////////////////////////////////////////////////////////////////
$('.thumb').click(function(){
  $(this).parent('form').find('.thumb_input').trigger('click');
});

$('.thumb_input').change(function(){
  $(this).closest('form').submit();
});

$('.thumb_form').submit(function(e) {
  var id = $(this).attr('fk_id');
  var table = $(this).attr('table');
  var img = $(this).find('img.thumb');
  img.attr('src', '/data/img/loading.gif');

  $.ajax({
          url: '/php/admin/thumb_upload.php?table='+table+'&id='+id,
          type: "POST",
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          success: function(data) {
           switch (data) {
             case 'invalid': alert('obrázek musí být ve formátu .jpg'); break;
             case 'error': alert('chyba uploadu'); break;
             default:
                if (data) {
                  img.attr('src', '/data/up/s/'+data);
                } else {
                  alert('chyba v uložení souboru');
                }
             break;
           }
          },
          error: function(e) {alert('wtf, si online?');}
  });

  e.preventDefault();
  return false;
});

// AKTIVOVAT ROCNIK
////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.changeyear', function(){
      $.get('/php/admin/rok_activate.php?to='+$(this).attr('to'), function(res){
        page('/admin/rok');
      });
});

// VYPUSTIT PROGRAMOVE FUNKCE
////////////////////////////////////////////////////////////////////////////////
function program_type() {
  switch ($('.programtype').val()) {
    case 'event': var svic = 'event1'; break;
    case 'blok': var svic = 'event2'; break;
    default: var svic = $('.programtype').val(); break;
  }
  switch(svic) {
    case 'event1':
      $('select[name="id_event1"]').closest('tr').removeClass('hidden');
      $('select[name="id_event2"]').closest('tr').addClass('hidden');
      $('select[name="id_event2"]').val("0");
    break;
    case 'event2':
      $('select[name="id_event1"]').closest('tr').addClass('hidden');
      $('select[name="id_event2"]').closest('tr').removeClass('hidden');
      $('select[name="id_event1"]').val("0");
    break;
    default:
      $('select[name="id_event1"], select[name="id_event2"]').closest('tr').addClass('hidden');
      $('select[name="id_event1"]').val("0");
      $('select[name="id_event2"]').val("0");
    break;
  }
}

$('.programtype').change(function(){
  program_type();
});

// EVENT TYP HRANY FILM APOD.? PRI EDITU
////////////////////////////////////////////////////////////////////////////////
function check_event() {
  if ($('.katselect option[value="film"]').is(':selected')) {
    $('select[name="id_kat"]').closest('tr').removeClass('hidden');
  }
  /*
  if ($('select[name="id_kat"] option:selected').length) {
    $('select[name="id_kat"]').closest('tr').removeClass('hidden');
  }
  */
}

// VYPUSTIT EVENT FUNKCE
////////////////////////////////////////////////////////////////////////////////
$('.katselect').change(function(){
  $('select[name="id_kat"]').val("0");
  switch($(this).val()) {
    case 'event':
      $('select[name="id_kat"]').closest('tr').addClass('hidden');
    break;
    case 'film':
      $('select[name="id_kat"]').closest('tr').removeClass('hidden');
    break;
    default:
      $('select[name="id_kat"]').closest('tr').addClass('hidden');
    break;
  }
});

// VLOZIT STABOVOU SABLONU
////////////////////////////////////////////////////////////////////////////////
$boy = 0;
$(document).on('click', '.entertits', function(){
    if ($boy == 0) {
      var enter = 'REŽIE.....\nSCÉNÁŘ.....\nPRODUKCE.....\nKAMERA.....\nZVUK.....\nSTŘIH.....\nVÝPRAVA.....\nHRAJÍ.....';
      enter = $('textarea[name="popis"]').val()+'\n'+enter;
      $('textarea[name="popis"]').focus();
      $('textarea[name="popis"]').val(enter);
      $boy = 1;
      setTimeout(function(){$boy = 0;}, 1000);
    }
});

// DUBLOVA SABLONU
////////////////////////////////////////////////////////////////////////////////
$(document).on('change paste keyup input', 'textarea[name="popis"]', function(){
  var input = $(this);
  var output = '';
  var en_output = '';
  if (input.val().indexOf('.....') > -1) {
    var lines = $(input).val().split('\n');
    var lines_en = $('textarea[name="popis_en"]').val().split('\n');
    for(var i = 0; i < lines.length; i++) {
      if (typeof lines[i] !== "undefined") {
         var pozice = lines[i].split('.....');
         if (pozice.length > 1) {
           switch (pozice[0]) {
             case 'REŽIE': var en_pozice = 'DIRECTOR'; break;
             case 'SCÉNÁŘ': var en_pozice = 'SCREENPLAY'; break;
             case 'PRODUKCE': var en_pozice = 'PRODUCTION'; break;
             case 'KAMERA': var en_pozice = 'DOP'; break;
             case 'ZVUK': var en_pozice = 'SOUND'; break;
             case 'STŘIH': var en_pozice = 'EDIT'; break;
             case 'VÝPRAVA': var en_pozice = 'LOCATIONS'; break;
             case 'SCÉNOGRAFIE': var en_pozice = 'SCENOGRAPHY'; break;
             case 'HRAJÍ': var en_pozice = 'CAST'; break;
             default: var en_pozice = pozice[0]; break;
           }
           output = output+en_pozice+'.....'+pozice[1]+'\n';
         }
      }
    }
    for(var i = 0; i < lines_en.length; i++) {
      if (typeof lines_en[i] !== "undefined") {
        var pozice = lines_en[i].split('.....');
        if (pozice.length > 1) {
          break;
        } else {
          en_output = en_output+lines_en[i]+'\n';
        }
      }
    }
    $('textarea[name="popis_en"]').val(en_output+output);
  }
});

// DB ENTRY
////////////////////////////////////////////////////////////////////////////////
$("#db_form").submit(function(e){

  var table = $('input[type="submit"]').attr('name');
  var akce = $('input[type="submit"]').attr('akce');
  var db_id = $('input[type="submit"]').attr('db_id');
  var list = 'table:"'+table+'"';

  $('#db_form input[type="text"], #db_form select, #db_form input[type="checkbox"], #db_form input[type="date"], #db_form input[type="time"], #db_form input[type="datetime-local"], #db_form textarea').each(function(){
    var name = $(this).attr('name');
    if ($(this).attr('type') == 'checkbox') {
      if ($(this).is(":checked")) {
        var val = 1;
      } else {
        var val = 0;
      }
    } else if ($(this).attr('type') == 'time') {
      var val = $(this).val().replace(/:/g, "/dvojtecka/");
    } else {
      switch (name) {
        case 'id_event1': case 'id_event2':
          if ($('select[name="id_event1"]').val() == "0") {
            var val = $('select[name="id_event2"]').val();
          } else {
            var val = $('select[name="id_event1"]').val();
          }
          name = 'id_event';
        break;
        default:
          var val = $(this).val();
        break;
      }
      val = val.replace(/,/g, "/carka/");
      val = val.replace(/"/g, "/uvozovka/");
      val = val.replace(/'/g, "/uvozovka2/");
      val = val.replace(/:/g, "/dvojtecka/");
      val = val.replace(/;/g, "/strednik/");
    }
    list = list+','+name+':'+'"'+val+'"';
  });

  $.post('/php/admin/sql.php', {list: list, akce: akce, id: db_id}, function(res){
    var ress = res.split('|');
    if (ress == 'done') {
      page('/admin/'+table+'/show');
    } else {
      switch (ress[0]) {
        case 'duplicate':
          var chyby = ress[1].split(',');
          $('input').removeClass('wrong');
          void $('input').width();
          $.each(chyby, function(index, value) {
            $('input[name="'+value+'"]').addClass('wrong');
          });
        break;
        default: case 'error':
          alert('ERROR: '+ress);
        break;
      }
    }
  });

  e.preventDefault();
  return false;

});

// V MENU ABY BYL ODKAZ NA ADMINISTRACI
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function (){

    if (!$('#menu_admin').length) {
      $('#menu').prepend('<li link="/admin" id="menu_admin" class="selected">ADMIN</li>');
    }

});

// DELETING
////////////////////////////////////////////////////////////////////////////////

$('.delete').click(function(e){
  if (!confirm('POTVRDIT SMAZÁNÍ')) {
    e.preventDefault();
  } else {
    var url = $(this).attr('link');
    page(url);
  }
});
