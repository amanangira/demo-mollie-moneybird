<?php

namespace Demo\Models;
require __DIR__ . '/../../vendor/autoload.php';

use Demo\ApplicationConstants;

class MySQLConnection
{
	protected static $connection;

	public static function getConnection()
	{
		if(!self::$connection)
		{
			$dbhost = "localhost";
			$dbuser = ApplicationConstants::MYSQL_USERNAME;// Create connection
			$dbpass = ApplicationConstants::MYSQL_PASSWORD;
                        $dbname = ApplicationConstants::MYSQL_DB_NAME;

			$mysqlConnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

			// Check connection
			if (!$mysqlConnection) {
			  throw new \Exception("Connection failed: " . mysqli_connect_error());
			}

			self::$connection = $mysqlConnection;
		}

		return self::$connection;
	}
}

?>
