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

function get_user_videos($hash, $start_date = null, $end_date = null)
{

	$query = "SELECT v.\"videoId\", v.date, u.email " 
	. "FROM videos as v "
	. "INNER JOIN "
	. "association as a "
	. "on v.\"videoId\" = a.\"videoId\" "
  	.	"INNER JOIN "
	. "users as u "
  	. "on a.email = u.email "
	. "WHERE u.email = (SELECT u.email WHERE u.hash = $1)";

	// return a link to a playlist for a given user
	if ($dbconn = pg_connect(DATABASE_INFO))
	{
		echo "CONNECTED TO POSGRESS\n</br>";

		echo "here is a list of all of the entries:\n</br>";
		$query_name = "get_videos_for_hash";

		// prepare the PDO statement
		$result = pg_prepare($dbconn, $query_name, $query);
		$result = pg_execute($dbconn, $query_name, array($_GET['id']));
		if ($result === false)
		{
			echo "error getting a result\n</br>";
		}
		else
		{
			$arr = pg_fetch_all($result);
			if ($arr === false)
			{
				echo "could not get array from posgress!\n</br>";
			}
			else
			{
				print_r($arr);
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

function insert_user($email)
{
	// return hash
}

function request_hash_reset($hash)
{
	// email user a link to reset hash
}

function reset_hash($hash, $email)
{
	// verify the temporary hash, then generate a new hash
}

function send_hash($email)
{
	// email the current hash to the user
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