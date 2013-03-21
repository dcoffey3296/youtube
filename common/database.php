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

function get_user_videos($email, $start_date = null, $end_date = null)
{

	// $query = "SELECT v.\"videoId\", v.date, a.email " 
	$query = "SELECT v.\"videoId\"	" 
	. "FROM videos as v "
	. "INNER JOIN "
	. "association as a "
	. "on v.\"videoId\" = a.\"videoId\" "
	. "WHERE a.email = $1";

// SELECT v."videoId", v.date, a.email
// FROM videos as v
// INNER JOIN
// association as a
// on v."videoId" = a."videoId"
// where a.email = 'danielpcoffey@gmail.com'



	// return a link to a playlist for a given user
	if ($dbconn = pg_connect(DATABASE_INFO))
	{
		$query_name = "get_videos_for_hash";

		// prepare the PDO statement
		$result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array($email));

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

function associate_email_to_video($email, $video)
{
	// associate a video with an email address
}

function remove_email_from_video($email, $video)
{
	// remove the user
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
	
	$body = "<html><head><title>YouTube Playlist</title></head>";
	$body .= "<body>Hi There!<br/>Here is the link to your playlist:<br/><br/>";
	$body .= $url . "<br/><br/>";
	$body .= "Hope you enjoy!<br/> xoxo, capture50</body>";

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