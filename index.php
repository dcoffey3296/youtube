<?php

	// require database info
	require_once(dirname(__FILE__)) . "/common/database.php";

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>youtube</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet"/>
		<link href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet"/>
		<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap.min.js"></script>
		<script src="common/js/common.js"></script>
		<link href="common/css/style.css" rel="stylesheet"/>
	</head>
	<body>
		<script type="text/javascript">
			$(document).ready(function(){ 
				$("#startdate").datepicker({altFormat: "yy-mm-dd", altField: "#start"});
				$("#enddate").datepicker({altFormat: "yy-mm-dd", altField: "#end"});
			});
		</script>
		<div class="outer">
			<div class="row">
			  <div class="span12">
				<form>
				  <fieldset>
				    <legend>capture50</legend>
				    <label>Email me my playlist!</label>
				    <input type="text" placeholder="you@cs50.net…" id="email">
				    <span class="help-block">Enter an email address and if you're in the system we'll mail you your playlist.</span>
				    <div id="datepickers">
					    <div class="input-prepend inline">
					      <span class="add-on"><i class="icon-calendar"></i></span>
					      <input class="datepicker" type="text" id="startdate" placeholder="start date (optional)"/>
					    </div>
					    <div class="input-prepend inline">
					      <span class="add-on"><i class="icon-calendar"></i></span>
					      <input class="datepicker" type="text" id="enddate" placeholder="end date (optional) "/>
					    </div>
				    	<input type="text" id="start" style="visibility:hidden"/>
				    	<input type="text" id="end" style="visibility:hidden"/>
				    </div>
				    <button type="button" class="btn" onclick="ajax('send_video_list');">Submit</button>
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
