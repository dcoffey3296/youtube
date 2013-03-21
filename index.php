<?php

	require_once(dirname(__FILE__)) . "/common/database.php";

	if (send_video_list("danielpcoffey@gmail.com"))
		echo "WORKED";
	else
		echo "FAILED";

?>