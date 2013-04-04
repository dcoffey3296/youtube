<?php

require_once(dirname(__FILE__)) . "/config.php";

// if (empty($_SESSION['user']) || $_SESSION['user'] != ADMIN_USER)
// {
// 	echo "empty session or user";
// 	echo "session={$_SESSION['user']}";
// 	exit;
// }

// // require admin to be logged in
// if ($_POST['user'] !== ADMIN_USER || $_POST['pass'] !== ADMIN_PASS)
// 	exit;

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
		
		<?php
			// keep JS hidden
			require_once "adminjs.php";
		?>

	</head>
	<body>
		<div class="outer">
			<div class="row">
			  <div class="span12">
				<form>
				  <fieldset>
				    <legend>capture50</legend>
				    <label>Lookup playlist URL</label>
				    <input type="text" placeholder="tf@cs50.net…" id="email" onblur="$('#button').focus();">
				    <span id="button" class="help-block">Enter an email address to get their playlist</span>
				    <div id="datepickers" style="margin-top:25px;">
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
				    <button type="button" class="btn" onclick="ajax('get_all_videos');">Submit</button>
				  </fieldset>
				</form>
			  </div>
			</div>
			<div id="container">
				<div id="message">
				</div>
				<div id="table">
				</div>
			</div>
			<div><a href="logout.php">logout</a></div>
		</div>
	</body>
</html>