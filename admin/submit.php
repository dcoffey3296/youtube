<?php

require_once(dirname(__FILE__)) . "/../common/constants.php";

// require user authentication
if (isset($_SERVER['PHP_AUTH_USER']) === false || ((strcmp($_SERVER['PHP_AUTH_USER'], SUBMIT_USER) !== 0) && (strcmp($_SERVER['PHP_AUTH_PW'], SUBMIT_PASS) !== 0)))
{
    header('WWW-Authenticate: Basic realm="capture50"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'invalid username and or password';
    exit;
} 

echo "HERE!";


?>