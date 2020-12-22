<?php

namespace Demo\Integrations;
require __DIR__ . '/../../vendor/autoload.php';

use Demo\Models\MoneybirdTokensRepository;
use \Picqer\Financials\Moneybird\Moneybird;
use \Picqer\Financials\Moneybird\Connection;
use Demo\ApplicationConstants;

class ClientFactory
{
	public function getMoneybirdConnection()
	{
		$connection = new Connection();
                $connection->setRedirectUrl(ApplicationConstants::MONEYBIRD_REDIRECT_URI);
                $connection->setClientId(ApplicationConstants::MONEYBIRD_CLIENT_ID);
                $connection->setClientSecret(ApplicationConstants::MONEYBIRD_CLIENT_SECRET);
		$connection->setRedirectUrl(ApplicationConstants::MONEYBIRD_REDIRECT_URI);

		return $connection;	
	}

	public static function getMoneybirdAuthenticatedClient()
	{
		$moneybirdRepository = new MoneybirdTokensRepository;
		$result = $moneybirdRepository->findOneByClientId(ApplicationConstants::MONEYBIRD_CLIENT_ID);
		
		if($result->num_rows < 1)
		{
			throw new \Exception("No valid access token in database. Re Authorize");
		}

		$row = $result->fetch_assoc();
		$accessToken = $row["access_token"];
		$connection = (self::getMoneybirdConnection());
		$connection->setAccessToken($accessToken);
		
		try {
		    $connection->connect();
		} catch (\Exception $e) {
		    throw new Exception('Could not connect to Moneybird: ' . $e->getMessage());
		}

		$moneybird = new Moneybird($connection);
		$administrations = $moneybird->administration()->getAll();
		$connection->setAdministrationId($administrations[0]->id);

		return $moneybird; 
	}
}
