
function ajax(action, data)
{
	$.ajax({
        type: "POST",
        url: "ajax.php",
        datatype: 'json',
        data:{ action: action, data: data },
        success: function(result){
			
			// parse JSON results
			var parsed = jQuery.parseJSON(result);
            if (parsed.error !== true) // if no error
            {
            	console.log(parsed);
            }
            else
            {
            	console.log(parsed);	
            }
        }
    });
}