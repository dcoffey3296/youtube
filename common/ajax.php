<?php
	if (!isset($_POST))
	{
		error_log("I don't know what you want to do.");
		exit(1);
	} 
	else if (!isset($_POST['action']))
	{
		error_log("I don't know what you want to do.");
	}
	switch ($_POST["action"])
	{
		case "send_video_list":
			$arr = json_decode($_POST['data']);
			print_r($arr);
		break;

		default:
			echo "something else";
		break;
	}

?>