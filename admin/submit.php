<?php

require_once(dirname(__FILE__)) . "/../common/constants.php";
require_once(dirname(__FILE__)) . "/../common/database.php";

// require user authentication
if (isset($_SERVER['PHP_AUTH_USER']) === false || ((strcmp($_SERVER['PHP_AUTH_USER'], SUBMIT_USER) !== 0) && (strcmp($_SERVER['PHP_AUTH_PW'], SUBMIT_PASS) !== 0)))
{
    header('WWW-Authenticate: Basic realm="capture50"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'invalid username and or password';
    exit;
} 

// make sure we have a post action
// check that we are supposed to upload the video we're passed
if (!isset($_POST))
{
    error_log("POST not set");
    exit(1);
}

switch ($_POST['action'])
{
    // insert a video or videos
    case "insert":

        if ((!isset($_POST['videos'])) || count($_POST['videos']) < 1)
        {
            error_log("no videos to insert!");
            echo "false";
        }

        // get a database handle
        $handle = get_connection();

        $failed = array();

        // check for array of videos
        foreach (unserialize($_POST['videos']) as $video)
        {
            $result = insert_video($handle, $video['videoId'], $video['date']);
            if ($result === false)
            {
                $failed[] = $video['title'];
            }
        }

        // close the DB connection
        pg_close($handle);

        // check for any failures
        if (count($failed) > 0)
        {
            error_log("error inserting video(s): " . implode(", ", $failed));
            echo "false";
        }
        else
        {
            echo "true";
        }
    break;

    // associate a video
    case "associate":
        
        // make sure that we got a video id and email to associate
        if (!isset($_POST['videoId']))
        {
            error_log("no video to associate!");
            echo "false";
        } 
        else if (!isset($_POST['emails']))
        {
            error_log("no emails to associate!");
            echo "false";
        }

        // get a database handle
        $handle = get_connection();
        $failed = array();

        // associate each passed email
        foreach (unserialize($_POST['emails']) as $email)
        {
            $result = associate_email_to_video($handle, $email, $_POST['videoId']);

            // check for success
            if ($result === false)
            {
                $failed[] = $_POST['videoId'] . ":" . $email;
            }
        }

        // close the DB connection
        pg_close($handle);

        // check for failures
        if (count($failed) > 0)
        {
            error_log("failed to associate: " . implode(", ", $failed));
            echo "false";
        }
        else
        {
            echo "true";
        }

    break;

    default:
        error_log("Unknown action: {$_POST['action']}");
        exit(1);
}


?>