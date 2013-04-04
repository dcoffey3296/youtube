<?php

    /***********************************************************************
     * config.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Configures pages.
     **********************************************************************/

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    require_once(dirname(__FILE__)) . "/../common/constants.php";
    require_once(dirname(__FILE__)) . "/../common/functions.php";

    // enable sessions
    session_start();

    // require authentication for most pages
    if (!preg_match("{(?:index|logout|login)\.php$}", $_SERVER["PHP_SELF"]))
    {
        if (empty($_SESSION["user"]))
        {
            redirect("index.php");
        }
    }

?>
