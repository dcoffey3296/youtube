<?php

	require_once(dirname(__FILE__)) . "/common/database.php";

	if (send_video_list($_GET['email']))
		echo "WORKED, you've been emailed";
	else
		echo "FAILED or user not found";

?>