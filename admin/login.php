<?php

    // configuration
    require_once(dirname(__FILE__)) . "/config.php";

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        // validate submission
        if (empty($_POST["user"]) || empty($_POST["pass"]))
        {
            redirect("index.php");
        }
        
        if ($_POST['user'] == ADMIN_USER && $_POST['pass'] == ADMIN_PASS)
        {
            $_SESSION['user'] = ADMIN_USER;
            redirect("admin.php");
        }
        else
        {
            redirect("index.php");
        }
    }
    else
    {
        // else render form
        redirect("index.php");
    }

?>
