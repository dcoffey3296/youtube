
function ajax(action, data)
{
	$.ajax({
        type: "POST",
        url: "switchboard.php",
        datatype: 'json',
        data:{ action: "get_people", login_code: login_code.toLowerCase()},
        success: function(result){
			
			// parse JSON results
			var parsed = jQuery.parseJSON(result);
            if (parsed.error !== true) // if no error
            {
            	// display data and clear errors
            	$("#login").slideUp(function(){
            		$(this).removeClass("error");
            		$("#announce").slideUp().removeClass("red").html("").removeClass("green");
            		window.rsvp = {};
            		window.rsvp.person = parsed.person;
            		window.rsvp.room = parsed.room;
            		window.rsvp.hotel = parsed.hotel;
            	
            		show_rsvp();
            	});
            }
            else
            {
            	// turn on error
            	$("#login").addClass("error").slideDown();
            	$("#announce").html("Code Not Recognized").removeClass("green").addClass("red").slideDown();
            	
            }
        }
    });
}