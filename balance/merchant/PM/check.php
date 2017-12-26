<?php
//check PerfectMoney payments
use HBOT\DB\Database;
use balance\merchant\classes\NDUSD;
require_once __DIR__ .'/../../../autoload.php';

require_once __DIR__ . '/../sci/PM.php';

		$hash =
		  $_POST['PAYMENT_ID'].':'.
		  $_POST['PAYEE_ACCOUNT'].':'.
		  $_POST['PAYMENT_AMOUNT'].':'.
		  $_POST['PAYMENT_UNITS'].':'.
		  $_POST['PAYMENT_BATCH_NUM'].':'.
		  $_POST['PAYER_ACCOUNT'].':'.
		  $ALTERNATE_PHRASE_HASH.':'.
		  $_POST['TIMESTAMPGMT'];
		  
		$hash=strtoupper(md5($hash));
		
		if($hash==$_POST['V2_HASH']){ // processing payment if only hash is valid

		   /* In section below you must implement comparing of data you received
		   with data you sent. This means to check if $_POST['PAYMENT_AMOUNT'] is
		   particular amount you billed to client and so on. */

		   //check if id(PAYMENT_ID) in db
				//get amount, currency ...								
				$db = Database::getDB();
				$sql = "SELECT 	
						`amount` 
						FROM 
						`poten_usd` 
						WHERE 
						`id` = ?";
				$row = $db -> selectRow($sql, [$_POST['PAYMENT_ID']]);
				
		   //PAYMENT_AMOUNT and PAYMENT_UNITS check
		   if($_POST['PAYMENT_AMOUNT']>=$row['amount'] && $_POST['PAYMENT_UNITS']=='USD'){

				/*
				INSERT INFO TO DATABASE:
				 - poten_pay status=1 + $_POST['PAYMENT_BATCH_NUM'] ???//+ time(now)
				 - AS NEW DEPOSIT + take from_poten pay some info
				 (it must be a class newDepoCreate )
				*/				
			  /* ...insert some code to process valid payments here... */
			  $ND = new NDUSD($_POST['PAYMENT_BATCH_NUM'], $_POST['PAYMENT_ID']); 
			  
			  // uncomment code below if you want to log successfull payments
			  /* $f=fopen(PATH_TO_LOG."good.log", "ab+");
			  fwrite($f, date("d.m.Y H:i")."; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n");
			  fclose($f); */

		   }else{ // you can also save invalid payments for debug purposes

			  // uncomment code below if you want to log requests with fake data
 			   // $dir = __DIR__ ."/../log/";
			   // $data = gmdate("d.m.Y H:i")."; REASON: fake data; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n";
			   // file_put_contents ( $dir."logPM.txt", $data, FILE_APPEND);
			  $Logger = new \Logger('checkPM.txt');
			  $Logger -> log('REASON: fake data', "POST: ".serialize($_POST)."; STRING: $string; HASH: $hash");

		   }


		}else{ // you can also save invalid payments for debug purposes

			// $dir = __DIR__ ."/../log/";
			// $data = gmdate("d.m.Y H:i")."; REASON: bad hash; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n";
			// file_put_contents ( $dir."logPM.txt", $data, FILE_APPEND);
			
		   	$Logger = new \Logger('checkPM.txt');
			$Logger -> log('REASON: bad hash', "POST: ".serialize($_POST)."; STRING: $string; HASH: $hash");

		} 
?>