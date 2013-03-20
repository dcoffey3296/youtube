<?php
	// getenv("DATABASE_URL");
	// getenv("HEROKU_POSTGRESQL_COBALT_URL");
	// getenv("DATABASE");
// credentials: dbname=d9bojppolrev2q host=ec2-107-22-183-27.compute-1.amazonaws.com port=5432 user=wpjhhgpttdbjkr password=g4agm1SLhgM6MyLe4g09UEFMDo sslmode=require

require_once(dirname(__FILE__)) . "/common/constants.php";

echo "this is the root dir\n</br>";
if ($dbconn = pg_connect(DATABASE_INFO))
{
	echo "CONNECTED TO POSGRES\n</br>";


	echo "here is a list of all of the entries:\n</br>";
	$query = "SELECT \"videoId\" FROM \"videos\" WHERE email = 'danielpcoffey@gmail.com'";

	if ($result = pg_query($dbconn, $query) === false)
	{
		echo "error getting a result\n</br>";
	}
	else
	{
		if ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC) === false)
		{
			echo "could not get array from posgress!\n</br>";
		}
		else
		{
			print_r($arr);
		}
	}



	echo "closeing DB\n</br>";
	pg_close($dbconn);
}
else
	echo "CONNECTION FAILED\n</br>";



?>