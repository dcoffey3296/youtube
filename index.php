<?php

	require_once(dirname(__FILE__)) . "/common/database.php";

	if (email("danielpcoffey@gmail.com", "test email from heroku", "Hey, hope you enjoy this email.  I'll see you this weekend!"))
		echo "WORKED";
	else
	{
		echo "FAILED";
	}

?>