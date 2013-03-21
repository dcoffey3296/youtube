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
		$result = pg_prepare($dbconn, $query_name, $query);
		$result = pg_execute($dbconn, $query_name, array($email));

		// check if it worked
		if ($result === false)
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
				error_log("could not get array from postgress!");
				return false;
			}
			else if (count($arr) < 1)
			{
				error_log("got no results back from query");
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
					error_log("no results back");
					return false;
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


function send_video_list($email)
{
	// email the current hash to the user
	$video_list = get_user_videos($email);

	if ($video_list === false)
	{
		error_log("didn't get any videos back");
		return false;
	}

	// store the first video
	$first_vid = $video_list[0];

	// remove the first video and merge the rest to csv
	array_splice($video_list, 0, 1);
	
	
	$body = "<html><head><title>YouTube Playlist</title></head>";
	$body .= "<body>Hi There!<br/>Here is the link to your playlist:<br/>";
	$body .= "http://youtube.googleapis.com/v/$first_vid";
	$body .= (count($video_list) > 0)? "?version=3&playlist=" . implode(",", $video_list) . "<br/>" : "<br/>";
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