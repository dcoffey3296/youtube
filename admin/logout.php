<?php

    // configuration
    require_once(dirname(__FILE__)) . "/config.php";

    // log out current user, if any
    logout();

    // redirect user
    redirect("index.php");

?>
