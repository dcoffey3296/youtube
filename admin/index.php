<?php

	require_once(dirname(__FILE__)) . "/../common/constants.php";


	if ($_POST['user'] == ADMIN_USER && $_POST['pass'] == ADMIN_PASS)
	{
		include(dirname(__FILE__) . "/admin.php");
	}
	else
	{
		include(dirname(__FILE__) . "/login.php");
	}
?>


