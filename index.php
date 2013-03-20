<?php
	// getenv("DATABASE_URL");
	// getenv("HEROKU_POSTGRESQL_COBALT_URL");
	// getenv("DATABASE");
// credentials: dbname=d9bojppolrev2q host=ec2-107-22-183-27.compute-1.amazonaws.com port=5432 user=wpjhhgpttdbjkr password=g4agm1SLhgM6MyLe4g09UEFMDo sslmode=require

require_once(dirname(__FILE__)) . "/common/constants.php";

echo "this is the root dir\n";
if ($dbconn = pg_connect(DATABASE_INFO))
{
	echo "CONNECTED TO POSGRES\n";
	echo "closeing DB";
	pg_close($dbconn);
}
else
	echo "CONNECTION FAILED";



?>