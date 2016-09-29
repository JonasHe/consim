(function($) {  // Avoid conflicts with other libraries
	'use strict';

	// define a couple constants for keydown functions.
	var keymap = {
		TAB: 9,
		ENTER: 13,
		ESC: 27
	};

	var $dark = $('#consim_darkenwrapper');
	var consim = {};
	consim.popupTime = 100;

	consim.popup = function(div_id) {
		var $popup = $(div_id);

		$(document).on('keydown.consim.popup', function(e) {
			if (e.keyCode === keymap.ENTER || e.keyCode === keymap.ESC) {
				consim.popup.close($popup, true);
				e.preventDefault();
				e.stopPropagation();
			}
		});
		consim.popup.open($popup);

		return $popup;
	};

		consim.popup.open = function($popup) {
		if (!$dark.is(':visible')) {
			$dark.fadeIn(consim.popupTime);
		}

		if ($dark.is(':visible')) {
			$dark.append($popup);
			$popup.fadeIn(consim.popupTime);
			maps.forEach(function(item, index) {
				item.updateSize();
				if(config[index]["focus"]!=0) {
					item.setFocus({region: config[index]["focus"]})
				}
			});
		} else {
			$dark.append($popup);
			$popup.show();
			$dark.fadeIn(consim.popupTime);
			maps.forEach(function(item,index) {
				item.updateSize();
				if(config[index]["focus"]!=0) {
					item.setFocus({region: config["focus"]})
				}
			});
		}

		$popup.on('click', function(e) {
			e.stopPropagation();
		});

		$dark.one('click', function(e) {
			consim.popup.close($popup, true);
			e.preventDefault();
			e.stopPropagation();
		});

		$popup.find('.popup_close').one('click', function(e) {
			consim.popup.close($popup, true);
			e.preventDefault();
		});
	};

	consim.popup.close = function($popup, fadedark) {
		var $fade = (fadedark) ? $dark : $popup;

		$fade.fadeOut(consim.popupTime, function() {
			$popup.hide();
		});

		$popup.find('.popup_close').off('click');
		$(document).off('keydown.consim.popup');
	};

	//COUNTDOWN
	consim.countdown = function($id) {
		var time = $($id).text().split(':');
		var min = time[0];
		var sec = time[1];
		var timer = setInterval(function() {
			sec = --sec;
			//Output
			if(sec < 10) {
				$($id).text(min + ':' + "0" + sec);
			} else {
				$($id).text(min + ':' + sec);
			}
			//Min to sec
			if(sec == 0 && min > 0) {
				sec = 60;
				--min;
			} else if (sec == 0) {
				clearInterval(timer);
				location.reload();
			}
		}, 1000);
	}

	$("input[name='travel']").click(function () {
		alert = consim.popup('#travel_popup');
	});

	$("a.province_map").click(function (event) {
		event.preventDefault();
		alert = consim.popup('#map_popup');
	});

	if($('#countdown').length) {
		consim.countdown($('#countdown'));
	}

})(jQuery); // Avoid conflicts with other libraries
