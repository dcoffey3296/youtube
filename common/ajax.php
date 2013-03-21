<?php
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
			$email = $_POST['email'];
			$result = get_user_videos($email);

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

		case "get_playlist_url":
			$email = $_POST['email'];
			$result = get_user_videos($email);

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
				$success = get_playlist_url($result);

				if ($success === false)
				{
					$return['error'] = true;
					$return['message'] = "We found your videos but there was an error emailing you.";
				}
				else
				{
					$return['error'] = false;
					$return['message'] = "$success";
				}
				
			}
		break;

		default:
			$return['error'] = true;
			$return['message'] = "I don't know what you wanted to do";
		break;
	}	

	echo json_encode($return);

?>