<?php

require_once(dirname(__FILE__)) . "/constants.php";
require_once(dirname(__FILE__)) . "/PHPMailer/class.phpmailer.php";
require_once(dirname(__FILE__)) . "/PHPMailer/class.smtp.php";

function get_connection()
{
	if ($dbconn = pg_connect(DATABASE_INFO))
	{
		return $dbconn;
	}
	else
	{
		error_log("could not connect to the database: " . pg_last_error($dbconn));
		return false;
	}
}

function get_user_videos($handle, $email, $start_date = null, $end_date = null)
{
	$arg_index = 2;
	$dates_array = array();

	// first arguement is email
	$pdo_array[] = $email;

	// remember the start date if specified
	if (isset($start_date))
	{
		$arg_array[] = "v.\"date\" >= $" . $arg_index;
		$pdo_array[] = $start_date;
		$arg_index++;
	}
	// remember the end date if specified
	if (isset($end_date))
	{
		$arg_array[] = "v.\"date\" <= $" . $arg_index;
		$pdo_array[] = $end_date;
		$arg_index++;
	}

	// $query = "SELECT v.\"videoId\", v.date, a.email " 
	$query = "SELECT v.\"videoId\"	" 
	. "FROM videos as v "
	. "INNER JOIN "
	. "association as a "
	. "on v.\"videoId\" = a.\"videoId\" "
	. "WHERE a.email = $1 ORDER BY v.\"date\" DESC";

	foreach ($arg_array as $arg)
	{
		$query .= " AND " . $arg;
	}

	// return a link to a playlist for a given user
	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", $pdo_array);

		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else if (pg_num_rows($result) === 0)
		{
			// no results
			return array();
		}
		else
		{
			// we got a valid response, parse it
			$arr = pg_fetch_all($result);

			// check the parsed results
			if ($arr === false)
			{
				error_log("got a postgress error");
				return false;
			}
			else
			{
				// merge the returned results into a single array and return the 
				$array = array();
				foreach ($arr as $id)
					$array[] = $id['videoId'];

				// make sure we have values
				if (count($array) > 0)
				{
					return $array;
				}
				else
				{
					return array();
				}
			}
		}
	}
}

function get_all_emails($handle)
{

	$query = "SELECT DISTINCT \"email\" FROM association";

	// return a link to a playlist for a given user
	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", array());
		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else
		{
			// we got a valid response, parse it
			$arr = pg_fetch_all($result);

			// check the parsed results
			if ($arr === false)
			{
				error_log("got a postgress error " . pg_last_error($handle));
				return false;
			}
			else
			{
				// merge the returned results into a single array and return the 
				
				$array = array();
				foreach ($arr as $result)
					$array[] = $result['email'];

				// make sure we have values
				return $array;
			}
		}
	}
}

function associate_email_to_video($handle, $email, $video)
{
	// this can happen for every video
	// associate a video with an email address
	// postgress timestamp date('Y-m-d H:i:s', time());

	// INSERT INTO videos ("videoId", date)
 	//    VALUES "abcccc", "2013-03-21 17:04:52"
	// WHERE NOT EXISTS (SELECT 1 FROM videos WHERE "videoId"="abccc")


	// $1 = videoId, $2 = email
	$query = "INSERT INTO association (\"videoId\", \"email\")("
	    . "SELECT $1, $2 "
	    . "WHERE NOT EXISTS ("
	        . "SELECT \"videoId\",\"email\" "
	        . "FROM association "
	        . "WHERE \"videoId\" = $1 AND \"email\" = $2"
	    . ")"
	. ")";

	// make sure we have a valid db handle
	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", array($video, $email));

		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else
		{
			// we had success
			return true;
		}
	}
}

// false on fail, true whether or not it existed before or not
// date = timestamp
function insert_video($handle, $video, $date)
{
	// insert a video if it doesn't exist
	// postgress timestamp date('Y-m-d H:i:s', time());

	// INSERT INTO videos ("videoId", date)
 //    VALUES "abcccc", "2013-03-21 17:04:52"
	// WHERE NOT EXISTS (SELECT 1 FROM videos WHERE "videoId"="abccc")

	// -- first insert:
	// $1 == videoId
	// $2 == date
	$query = "INSERT INTO videos (\"videoId\", \"date\")("
		. "SELECT $1, $2 "
		. "WHERE NOT EXISTS ("
			. "SELECT \"videoId\" " 
			. "FROM videos "
			. "WHERE \"videoId\" = $1"
		. ")"
	. ")";


	// make sure we have a valid db handle
	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", array($video, date('Y-m-d H:i:s', $date)));

		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else
		{
			// return number inserted (0 if exists, 1 if newt_bell(oid))
			return true;
		}
	}
}


function remove_email_from_video($handle, $email, $video)
{
	// remove the user from a given video
	$query = "DELETE FROM ONLY association WHERE \"email\" = $1 and \"videoId\" = $2";

	// make sure we have a valid db handle
	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", array($email, $video));

		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else
		{
			// return 
			return true;
		}
	}
}

function remove_video($handle, $video)
{
	// remove a video from the database
	$query = "DELETE FROM ONLY videos WHERE \"videoId\" = $1";

	if ($handle !== false)
	{
		// prepare the PDO statement
		$result = pg_prepare($handle, "", $query);
		$result = pg_execute($handle, "", array($video));

		// check if it worked
		if (pg_num_rows($result) < 0)
		{
			error_log("error getting a result");
			return false;
		}
		else
		{
			// return 
			return true;
		}
	}

}

function get_playlist_url($video_list)
{
	// check for videos
	if (count($video_list) === 0)
	{
		error_log("no videos in video list");
		return false;
	}

	// set the first video
	$str = "http://youtube.googleapis.com/v/" . $video_list[0];

	// remove the first video and merge the rest to csv
	array_splice($video_list, 0, 1);
	$str .= (count($video_list) > 0)? "?version=3&playlist=" . implode(",", $video_list) : "";

	return $str;
}

function send_video_list($email, $video_list)
{
	// email the current hash to the user
	

	if (count($video_list) === 0)
	{
		error_log("no videos in video list");
		return false;
	}

	$url = get_playlist_url($video_list);
	if ($url === false)
	{
		error_log("could not get playlist url");
		return false;
	}
	
	$body = "<html><head><title>YouTube Playlist</title></head>"
		. "<body>Hi There!<br/>Here is the link to your playlist:<br/><br/>"
		. $url . "<br/><br/>"
		. "Hope you enjoy!<br/> xoxo, capture50</body>";

	$result = email($email, "YouTube Playlist for $email", $body);
	return $result;
}

function email($to, $subject, $body)
{
    $mail = new PHPMailer();

    // set subject
    $mail->Subject = "[capture50] $subject";

    // set the body of the email
    $mail->Body = $body;

    // use HTML email
    $mail->isHTML(true);

    // add a recipient
    $mail->AddAddress($to);

    // set to use SMTP
    $mail->IsSMTP();
    $mail->Host = SMTP_SERVER;
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; 
    $mail->SMTPDebug = 1;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;  
	$mail->Password = SMTP_PASS;   

     
    // set from
    $mail->SetFrom(FROM_EMAIL);

    // send 
    if ($mail->send())
        return true;
    else
        return false;
}

?>