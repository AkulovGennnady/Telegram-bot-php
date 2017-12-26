<?php
//check AdvCash payments
use balance\merchant\classes\NDUSD;
use HBOT\DB\Database;
require __DIR__ .'/../../../autoload.php';

require_once __DIR__ . '/../sci/AC.php';
 
		$string = 
		$_POST['ac_transfer'].':'.
		$_POST['ac_start_date'].':'.
		$_POST['ac_sci_name'].':'.
		$_POST['ac_src_wallet'].':'.
		$_POST['ac_dest_wallet'].':'.
		$_POST['ac_order_id'].':'.
		$_POST['ac_amount'].':'.
		$_POST['ac_merchant_currency'].':'.
		$SCISP;
		
		$hash = hash('sha256', $string);
		
		if($hash==$_POST['ac_hash']){ // processing payment if only hash is valid

		   /* In section below you must implement comparing of data you received
		   with data you sent. This means to check if $_POST['PAYMENT_AMOUNT'] is
		   particular amount you billed to client and so on. */

		   //check if id(ac_order_id) in db
				//get amount, currency ...
								
				$db = Database::getDB();
				$sql = "SELECT 	
						`amount`
						FROM 
						`poten_usd` 
						WHERE 
						`id` = ?";
				$row = $db -> selectRow($sql, [$_POST['ac_order_id']]);
				
		   //PAYMENT_AMOUNT and PAYMENT_UNITS check
		   if($_POST['ac_amount']>=$row['amount'] && $_POST['ac_merchant_currency']=='USD'){

				/*
				INSERT INFO TO DATABASE:
				 - poten_pay status=1 + $_POST['PAYMENT_BATCH_NUM'] ???//+ time(now)
				 - AS NEW DEPOSIT + take from_poten pay some info
				 (it must be a class newDepoCreate )
				*/				
			  /* ...insert some code to process valid payments here... */
			  $ND = new NDUSD($_POST['ac_transfer'], $_POST['ac_order_id']);
			  
			  // uncomment code below if you want to log successfull payments
			  /* $f=fopen(PATH_TO_LOG."good.log", "ab+");
			  fwrite($f, date("d.m.Y H:i")."; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n");
			  fclose($f); */

		   }else{ // you can also save invalid payments for debug purposes

			  // uncomment code below if you want to log requests with fake data
 			 /*   $dir = __DIR__ ."/../log/";
			   $data = gmdate("d.m.Y H:i")."; REASON: fake data; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n";
			   file_put_contents ( $dir."logAC.txt", $data, FILE_APPEND); */
			   
			    $Logger = new \Logger('checkAC.txt');
			    $Logger -> log('REASON: fake data', "POST: ".serialize($_POST)."; STRING: $string; HASH: $hash");	
				
		   }


		}else{ // you can also save invalid payments for debug purposes

		/* 	$dir = __DIR__ ."/../log/";
			$data = gmdate("d.m.Y H:i")."; REASON: bad hash; POST: ".serialize($_POST)."; STRING: $string; HASH: $hash\n";
			file_put_contents ( $dir."logAC.txt", $data, FILE_APPEND); */
			
			 $Logger = new \Logger('checkAC.txt');
			 $Logger -> log('REASON: bad hash', "POST: ".serialize($_POST)."; STRING: $string; HASH: $hash");

		}
?>
  