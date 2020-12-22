<?php

namespace Demo\Integrations;
require __DIR__ . '/../../../vendor/autoload.php';

use Picqer\Financials\Moneybird\Entities\Contact as MoneybirdContact;
use Demo\Integrations\ClientFactory;

$contact = (ClientFactory::getMoneybirdAuthenticatedClient())->contact();

if(isset($_POST["submit"]) && $_POST["submit"] == "submit")
{
	$request = $_POST;
        $contact->company_name = $request["companyname"];
        $contact->firstname = $request["firstname"];
        $contact->lastname = $request["lastname"];
        $response = $contact->save();
        
        if($response->id)
            echo "Contact created succesfully with ID : $response->id";	
}

?>


<!-- Create Action Block-->
<?php

if(isset($_GET["action"]) && $_GET["action"] == "create")
{
?>
<html>
<head>
<title>Moneybird | Contact</title>
<head>
<body>
    <form method = "post">
        <label for="companyname">Company name:</label><br>
        <input type="text" id="companyname" name="companyname"><br>
        <label for="firstname">First name:</label><br>
        <input type="text" id="firstname" name="firstname"><br>
        <label for="lastname">Last name:</label><br>
        <input type="text" id="lastname" name="lastname">
        <input type="submit" name="submit" value="submit">
    </form>  
    <br>
    <a href="/src/Integrations/Moneybird/Callback.php">Go to main page</a>
</body>
</html>

<?php } ?> 

<!-- List Action Block-->
<?php

if(isset($_GET["action"]) && $_GET["action"] == "list")
{
?>

<html>
<head>
<title>Moneybird | Contact</title>
<head>
<body> 
<?php 
	$contacts = $contact->getAll();
	$counter = 1;
	echo "Total Contacts : " . count($contacts) . PHP_EOL;
	foreach($contacts as $contact)
	{
		echo $counter . ". Contact Id : $contact->id";
		echo "  Company Name : $contact->company_name";
		echo "  First Name : $contact->firstname";
		echo "  Last Name : $contact->lastname" . PHP_EOL;
		$counter++;
	}
    ?>
    <br>
    <a href="/src/Integrations/Moneybird/Callback.php">Go to main page</a>
</body>
</html>

<?php } ?>
