$(document).ready(function() {
	$(function() {
		var timer = 0;
		var items = [];

		$.each($('nav.top > div'), function(i, v) {
			if($(v).has('> div').length > 0) {
				items.push({ el: $(v), timer: 0 });
			}
		});

		$.each(items, function(i, item) {
			item.el.hover(function() {
				clearTimeout(item.timer);

				item.el.addClass('opened');
			}, function() {
				item.timer = setTimeout(function() {
					item.el.removeClass('opened');
				}, 300);
			});
		});
	});
});

/*function getWindowWidth() {
	var windowWidth = 0;

	if (typeof(window.innerWidth) == 'number') {
		windowWidth = window.innerWidth;
	}

	else {
		if (document.documentElement && document.documentElement.clientWidth) {
			windowWidth = document.documentElement.clientWidth;
		} else {
			if (document.body && document.body.clientWidth) {
				windowWidth = document.body.clientWidth;
			}
		}
	}

	return windowWidth;
}

$(document).ready(function() {
	$menu = $('.mobile-menu');

	$('a.toggle-menu').click(function() {
		$menu.slideToggle("fast");
	})

	$(window).resize(function() {
		var ww = getWindowWidth();

		if(ww > 767) {
			$menu.css('display', 'flex');
		} else {
			if($menu.is(':visible')) {
				$menu.css('display', 'block');
			}
		}
	});
});*/