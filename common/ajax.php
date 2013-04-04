<?php 

	require_once(dirname(__FILE__)) . "/constants.php";
	require_once(dirname(__FILE__)) . "/database.php";

	if (!isset($_POST))
	{
		error_log("I don't know what you want to do.");
		exit(1);
	} 
	else if (!isset($_POST['action']))
	{
		error_log("I don't know what you want to do.");
		exit(1);
	}
	
	$return = array();
	switch ($_POST["action"])
	{

		case "send_video_list":
		
			$handle = get_connection();
			if ($handle === false)
			{
				error_log("unable to get db connection");
				return false;
			}

			$email = $_POST['email'];

			// set the start date appropriately or to null
			if ($_POST['startDate'] != "")
				$start = $_POST['startDate'];
			else
				$start = null;

			if ($_POST['endDate'] != "")
				$end = $_POST['endDate'];
			else
				$end = null;

			$result = get_user_videos($handle, $email, $start, $end);

			pg_close($handle);

			if ($result === false)
			{
				$return['error'] = true;
				$return['message'] = "Sorry, there was an error.";
			}
			else if (count($result) === 0)
			{
				$return['error'] = true;
				$return['message'] = "Sorry, we have no videos for $email";
			}
			else
			{
				// we have an array of > 0 videos here, email the user
				$success = send_video_list($email, $result);

				if ($success === false)
				{
					$return['error'] = true;
					$return['message'] = "We found your videos but there was an error emailing you.";
				}
				else
				{
					$return['error'] = false;
					$return['message'] = "Thanks, your playlist has been emailed to $email.";
				}
				
			}
		break;

		default:
			$return['error'] = true;
			$return['message'] = "I don't know what you wanted to do";
	}	

	echo json_encode($return);

?>