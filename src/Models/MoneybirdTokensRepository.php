<?php

namespace Demo\Models;
require __DIR__ . '/../../vendor/autoload.php';

use Demo\Models\MySQLConnection;

class MoneybirdTokensRepository
{

	protected $tableName = 'moneybird_tokens';

	protected $connection;

	public function __construct($connection = null)
	{
		if(!$connection)
		{
			$connection = MySQLConnection::getConnection();
		}

		$this->connection = $connection;
	}

	public function findOneByClientId($clientID)
	{
		$sql = "SELECT * FROM $this->tableName WHERE client_id = '".$clientID."' ORDER BY id  DESC LIMIT 1";
		$result = $this->connection->query($sql);

		return $result;
	}

	public function deleteByClientId($clientID)
	{
		$sql = "DELETE FROM $this->tableName WHERE client_id = '".$clientID."'";
                return $this->connection->query($sql);
	}

	public function insert($clientId, $accessToken, $refreshToken, $tokenType, $scope, $createdAt)
	{
		$createdAt = "FROM_UNIXTIME('$createdAt')";
		$sql = "INSERT INTO $this->tableName (client_id, access_token, refresh_token, scope, token_type, created_at, updated_at) values('" . $clientId . "', '" . $accessToken . "', '" . $refreshToken . "', '" . $tokenType . "', '" . $scope . "', $createdAt, $createdAt)";
		
		
		return $this->connection->query($sql);
	}
}
	
