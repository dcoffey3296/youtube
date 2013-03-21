<?php
	// getenv("DATABASE_URL");
	// getenv("HEROKU_POSTGRESQL_COBALT_URL");
	// getenv("DATABASE");
// credentials: dbname=d9bojppolrev2q host=ec2-107-22-183-27.compute-1.amazonaws.com port=5432 user=wpjhhgpttdbjkr password=g4agm1SLhgM6MyLe4g09UEFMDo sslmode=require

/***
SELECT v."videoId", v.date, u.email
FROM videos as v
  INNER JOIN
association as a
  on v."videoId" = a."videoId"
  INNER JOIN
users as u
  on a.email = u.email
WHERE u.email = (SELECT u.email WHERE u.hash = 'abcd1234')
*/
require_once(dirname(__FILE__)) . "/common/constants.php";

$query = "SELECT v.\"videoId\", v.date, u.email " 
	. "FROM videos as v "
	. "INNER JOIN "
	. "association as a "
	. "on v.\"videoId\" = a.\"videoId\" "
  	.	"INNER JOIN "
	. "users as u "
  	. "on a.email = u.email "
	. "WHERE u.email = (SELECT u.email WHERE u.hash = '{$_GET["id"]}')";
// $query = pg_escape_string($query);



echo "query: $query\n</br>";
if ($dbconn = pg_connect(DATABASE_INFO))
{
	echo "CONNECTED TO POSGRESS\n</br>";

	echo "here is a list of all of the entries:\n</br>";

	$result = pg_query($dbconn, $query);
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



	echo "closeing DB\n</br>";
	pg_close($dbconn);
}
else
	echo "CONNECTION FAILED\n</br>";



?>