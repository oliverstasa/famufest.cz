var reload_time = 20; // seconds

$(window).ready(function(){
  live();
  counter();
});

function live() {

    $.post('/php/document/livecounter.php', {reload: reload_time}, function(res) {
      $('#livecount').html(res);
    });
    return true;

}

function counter() {

  setTimeout(function(){
     if (live()) {
       counter();
     }
  }, reload_time*1000);

}
