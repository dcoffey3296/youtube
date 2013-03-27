
function ajax(action)
{
    switch (action)
    {
        case "send_video_list":
            $.ajax({
                type: "POST",
                url: "common/ajax.php",
                datatype: 'json',
                data:{ action: action, email: $("#email").val(), startDate: $("#start").val(), endDate: $("#end").val() },
                success: function(result){
                    
                    // parse JSON results
                    var parsed = jQuery.parseJSON(result);
                    console.log(parsed);
                    if (parsed.error !== true) // if no error
                    {
                        $("#message").text(parsed.message);
                    }
                    else
                    {
                        $("#message").text(parsed.message);
                    }
                }
            });
        break;

        default:
            return false;
    }
}