<?php

	require_once(dirname(__FILE__)) . "/common/database.php";

?>
<!DOCTYPE html>
<html>
	<head>
		<title>youtube</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet"/>
		<link href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet"/>
		<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap.min.js"></script>
		<script src="common/js/common.js"></script>
	</head>
	<body>
		<div class="row">
		  <div class="span6">
			<form>
			  <fieldset>
			    <legend>capture50</legend>
			    <label>Email me my playlist!</label>
			    <input type="text" placeholder="you@cs50.net…" id="email">
			    <span class="help-block">Enter an email address and if you're in the system we'll mail you your playlist.</span>
			    <button type="button" class="btn" onclick="ajax('send_video_list', {'email':'$(#email).val()'});">Submit</button>
			  </fieldset>
			</form>
		  </div>
		</div>
	</body>
</html>
