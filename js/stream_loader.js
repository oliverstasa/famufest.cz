$(window).ready(function() {

  $.get('/php/stream_linker.php?idle=countdown', function(data) {

    if (data != 0) {
      clearInterval(window.checker);
      startTimer(data);
    }

  });

});

function startTimer(dur) {

    var start = Date.now(), diff, min, sec, res, checker;

    function timer() {

        diff = dur - (((Date.now() - start) / 1000) | 0);

        hrs = (diff / 3600) | 0;
        min = (diff / 60) | 0;
        sec = (diff % 60) | 0;

        hrs = hrs < 10 ? "0" + hrs : hrs;
        min = min < 10 ? "0" + min : min;
        sec = sec < 10 ? "0" + sec : sec;

        $("#streamidle").html(hrs+":"+min+":"+sec);

        if (diff <= 0) {

            clearInterval(window.checker);
            $.get('/php/stream_linker.php?idle=getlink', function(data) {

              if (data) {
                if (data.indexOf('href="live"') != -1) {
                  // $("#platno .video").addClass('loading');
                }
                $("#platno .video").html(data);
              }

            });

        }
    };

    timer();
    window.checker = setInterval(timer, 1000);

}
