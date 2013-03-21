
function ajax(action)
{
    switch (action)
    {
        case "send_video_list":
            $.ajax({
                type: "POST",
                url: "common/ajax.php",
                datatype: 'json',
                data:{ action: action, email: $(#email).val() },
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
        break;

        default:
            return false;
    }
}