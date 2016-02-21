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

    consim.popup = function() {
    	var $popup = $('#travel_popup');

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
    	} else {
    		$dark.append($popup);
    		$popup.show();
    		$dark.fadeIn(consim.popupTime);
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


    $("input[name='travel']").click(function () {
        alert = consim.popup();
    });
})(jQuery); // Avoid conflicts with other libraries
