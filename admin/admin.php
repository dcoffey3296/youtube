<?php

require_once(dirname(__FILE__)) . "/../common/constants.php";

// require admin to be logged in
if ($_POST['user'] !== ADMIN_USER || $_POST['pass'] !== ADMIN_PASS)
	exit(0);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>admin youtube</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet"/>
		<link href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet"/>
		<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap.min.js"></script>
		<link href="../common/css/style.css" rel="stylesheet"/>
		<script type="text/javascript">

		$(document).ready(ajax("get_all_emails"));
		
		function ajax(action)
		{
		    switch (action)
		    {
		        case "get_playlist_url":
		            $.ajax({
		                type: "POST",
		                url: "../common/ajax.php",
		                datatype: 'json',
		                data:{ action: "get_playlist_url", email: $("#email").val() },
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

		        case "get_all_emails":
            $.ajax({
                type: "POST",
                url: "../common/ajax.php",
                datatype: 'json',
                data:{ action: action, email: $("#email").val() },
                success: function(result){
                    
                    // parse JSON results
                    var parsed = jQuery.parseJSON(result);
                    if (parsed.error !== true) // if no error
                    {
                        $("#email").autocomplete({source: parsed.data});
                    	console.log(parsed.data);

                    }
                    else
                    {
                        console.log("error getting response");
                        console.log(parsed);
                    }
                }
            });
        break;

		        default:
		            return false;
		    }
		}
		</script>

	</head>
	<body>
		<div class="outer">
			<div class="row">
			  <div class="span12">
				<form>
				  <fieldset>
				    <legend>capture50</legend>
				    <label>Lookup playlist URL</label>
				    <input type="text" placeholder="tf@cs50.net…" id="email">
				    <span class="help-block">Enter an email address to get their playlist</span>
				    <button type="button" class="btn" onclick="ajax('get_playlist_url');">Submit</button>
				  </fieldset>
				</form>
			  </div>
			</div>
			<div class="row">
				<div class="span12">
					<div id="message"></div>
				</div>
			</div>
		</div>
	</body>
</html>