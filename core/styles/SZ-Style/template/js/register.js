(function($) {  // Avoid conflicts with other libraries

'use strict';

$(function() {

    //speichert den alten Wert im aktivem Objekt.
    //bei mouseenter (focus functioniert nicht mit dem type=number)
    $('#attribute .inputbox').mouseenter(function (element) {
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
});

})(jQuery); // Avoid conflicts with other libraries
