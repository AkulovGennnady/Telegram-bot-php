<?php
//check BTC payments
use balance\merchant\classes\NDBTC;
require __DIR__ .'/../../../autoload.php';

require __DIR__ . '/../sci/BTC.php';

if($_POST["hash"] == md5($access_key.$_POST["txid"].$_POST["apibtc_id"].$_POST["address"]))
	{
		//check confirmations
		//if ($_POST["confirmations"] >= 3)
		//{	
		//Update the payment information in the database
		
		//update poten pay
		//create new depo
		$NDBTC = new NDBTC($_POST["amount"], $_POST["address"], $_POST["txid"], $_GET['ID']);
		
		echo "Successfully|".$_GET['ID'];
		//}else 
		//{
			//pending payments
		//}
	}else 
	{
		$Logger = new \Logger('checkAC.txt');
	$Logger -> log('REASON: bad hash or ID', "POST: ".serialize($_POST)."; ID: {$_GET['ID']}; HASH: {$hash}");

	}

?>


  