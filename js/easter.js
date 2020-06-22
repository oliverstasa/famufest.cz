function lets_dance() {
	var delay = 0;
	$('#menu li').each(function(){
		var li = $(this);
		setTimeout(function(){
			li.addClass('klavir');
		}, delay);
		setTimeout(function(){
			li.removeClass('klavir');
		}, delay+250);
		delay = delay+100;
	});
}

const bubi = 'bubi'.split(''); let bubiIn = new Array(bubi.length);

const arcade = 'arcade'.split(''); let arcaIn = new Array(arcade.length);
const petr = 'petrmichal'.split(''); let petrIn = new Array(petr.length);
const score = 'score'.split(''); let scorIn = new Array(score.length);

const hamburgr = 'hamburger'.split(''); let hambIn = new Array(hamburgr.length);

const cheats = new Array('beatamrazikova', 'danielbleha', 'krystofdavid', 'krystofburda', 'filipkopecky', 'oliverstasa', 'juras');
const cheat_arrays = new Array();
const cheat_checker = new Array();
cheats.forEach(function(i) {
	cheat_arrays.push(i.split(''));
		var pole = new Array(i.length);
	cheat_checker.push(pole);
});

window.addEventListener('keydown', ({key}) => {

	cheats.forEach(function(item, i) {

		cheat_checker[i] = [ ...cheat_checker[i].slice(1), key ];
		if (cheat_arrays[i].every((v, k) => v === cheat_checker[i][k])) {
			lets_dance();
		}

	});

	bubiIn = [ ...bubiIn.slice(1), key ];
  arcaIn = [ ...arcaIn.slice(1), key ];
  scorIn = [ ...scorIn.slice(1), key ];
  hambIn = [ ...hambIn.slice(1), key ];
	petrIn = [ ...petrIn.slice(1), key ];

  if (bubi.every((v, k) => v === bubiIn[k])) {
    $('body').prepend('<div id="easter"><div>BUBI LOVE</div></div>');
    setTimeout(function() {
      $('#easter').fadeOut(2000, function(){
        $(this).remove();
      });
    }, 100);
	}

  if (hamburgr.every((v, k) => v === hambIn[k])) {
    $('#hamburgr').html('🍔');
  }

  if (arcade.every((v, k) => v === arcaIn[k]) || petr.every((v, k) => v === petrIn[k])) {
    if ($('#score').length) { $('#score').remove(); }
    if (!$('#game').length) {
      $('body').prepend('<script type="text/javascript" src="/js/fighter.js">');
    }
	}

  if (score.every((v, k) => v === scorIn[k])) {
    if ($('#game').length) { $('#game').remove(); }
    if (!$('#score').length) {
      $('body').prepend('<script type="text/javascript" src="/js/score.js">');
    }
	}
});
