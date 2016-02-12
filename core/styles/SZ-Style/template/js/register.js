(function($) {  // Avoid conflicts with other libraries

'use strict';

$(function() {

    //speichert den alten Wert im aktivem Objekt.
    //bei mouseenter (focus functioniert nicht mit dem type=number)
    $('#attribute .inputbox').on('mouseenter focus', function () {
		$(this).data('oldVal', $(this).val());
	});

    //change free_points if changed a inbox
	$('#attribute .inputbox').on('change', function () {
		var attr = parseInt($('#free_points').text());
        //new value
        var int = $(this).val();
        //calculate the difference
        var diff = int - $(this).data('oldVal');
        //new old Value
        $(this).data('oldVal', int);
        //new free points
        attr = attr - diff;
        $('#free_points').text(attr);

        if(attr == 0) {
            $('#free_points').addClass("green");
        }
        else {
            $('#free_points').removeClass("green");
        }

	});

    //show info if site reloaded
    add_25_lang($('#geburtsland').val());

    //show info for extra skills
	$('#geburtsland').on('change', function () {
        add_25_lang($(this).val());
	});

    //function to show lang info
    function add_25_lang(wert)
    {
        if(wert == 'frt') {
            $('#lang_frt').show();
            $('#lang_bak').hide();
            $('#lang_sur').hide();
        } else if (wert == 'bak') {
            $('#lang_frt').hide();
            $('#lang_bak').show();
            $('#lang_sur').hide();
        } else if (wert == 'sur') {
            $('#lang_frt').hide();
            $('#lang_bak').hide();
            $('#lang_sur').show();
        }
    }
});

})(jQuery); // Avoid conflicts with other libraries
