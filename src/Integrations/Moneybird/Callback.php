<?php

namespace Demo\Services\Outbound;
require __DIR__ . '/../../../vendor/autoload.php';

use Demo\ApplicationConstants;
use Demo\Models\MoneybirdTokensRepository;

$dateTime = (new \DateTime())->format("[Y-m-d H:i:s]");
$entityBody = file_get_contents('php://input');

$request = [
    "RAW" => $entityBody,
    "POST" => $_POST,
    "GET"  => $_GET
];

$content = $dateTime . json_encode($request) . PHP_EOL;

file_put_contents(ApplicationConstants::LOG_FILE_PATH, $content, FILE_APPEND);

if(isset($_GET["code"]))
{
    $tempCode = $_GET["code"];
    $responseString = exchangeCodeForTokens($tempCode);
    $response = json_decode($responseString, true);

    if(JSON_ERROR_NONE !== json_last_error())
    {
        echo "Something went wrong - Unable to fetch tokens from moneybird. Invalid JSON received.";
        return;
    }


    $moneybirdRepository  = new MoneybirdTokensRepository;

    $moneybirdRepository->deleteByClientId(ApplicationConstants::MONEYBIRD_CLIENT_ID);
    $result = $moneybirdRepository->insert(ApplicationConstants::MONEYBIRD_CLIENT_ID, $response["access_token"], $response["refresh_token"], $response["token_type"], $response["scope"], $response["created_at"]);
	
    if($result)
    {
    	echo "Token Refresh Succesful.";
    }

    return;
}

function exchangeCodeForTokens($code)
{
    $data = [
        "client_id" => ApplicationConstants::MONEYBIRD_CLIENT_ID,
        "client_secret" => ApplicationConstants::MONEYBIRD_CLIENT_SECRET,
        "redirect_uri" => ApplicationConstants::MONEYBIRD_REDIRECT_URI,
        "code" => $code,
        "grant_type" => "authorization_code"
    ];

    $query = http_build_query($data);
    $endpoint = ApplicationConstants::MONEYBIRD_TOKEN_URL . "?$query";

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $endpoint);
    curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    curl_close ($ch);

    return $response;
}
?>

<!DOCTYPE html>
<html>
   <head>
      <title>Title of the document</title>
   </head>
   <body>
      <a href="<?php echo ApplicationConstants::MONEYBIRD_AUTHORIZE_URL_TEMPLATE;?>" >1. MoneyBird - Refresh Tokens</a>
	<br>
      <a href="/src/Integrations/Moneybird/Contact.php?action=create" >2. MoneyBird - Create Contact</a>
	<br>
      <a href="/src/Integrations/Moneybird/Contact.php?action=list" >3. MoneyBird - List Contacts</a>
   </body>
</html>
