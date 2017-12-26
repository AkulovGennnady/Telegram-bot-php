<?php

use HBOT\DB\Database;
require __DIR__ . '/../autoload.php';

if (isSHA1())
	createInv();		
	 
	function isSHA1()
	{
		if (!$_GET['descr'] AND !$_GET['pay']) return 0;
		if (preg_match('/^[a-f0-9]{40}$/', $_GET['descr']))
			return 1;
		
		$Logger = new \Logger('addfunds.txt');
		$Logger -> log('Input errror', $_GET['descr']);		
		return 0;
	}
	function createInv()
	{
		//get amount, paym_sys...
		$db = Database::getDB();
		
		switch ($_GET['pay']){
			case 'AC':
			case 'PM':
			case 'PY':	
				//select
				$sql = "SELECT 	
				`id`,`amount`,`paym_sys`
				FROM 
				`poten_usd` 
				WHERE 
				`descr` = ?";
				$row = $db -> selectRow($sql, [$_GET['descr']]);
				//run function				
				$row['paym_sys']($row['id'], $row['amount']);				
				break;
				
			case 'BTC':
				//select
				$sql = "SELECT 	
				`id`,`amount`, `wallet_to`
				FROM 
				`poten_BTC` 
				WHERE 
				`descr` = ?";
				$row = $db -> selectRow($sql, [$_GET['descr']]);
			
				BTC($row['id'], $row['amount'], $row['wallet_to']);						
				break;			
		}
		
	}
	
	//perfect money invoice 
	function PM($id, $amount)
	{
		require __DIR__ . '/merchant/sci/'.__FUNCTION__.'.php';
		
		$inv = '
		<body onload="document.forms[0].submit()">

			<form style="display:none;" action="https://perfectmoney.is/api/step1.asp" method="POST">
			<input type="hidden" name="PAYEE_ACCOUNT" value="'.$PAYEE_ACCOUNT.'">
			<input type="hidden" name="PAYEE_NAME" value="'.$PAYEE_NAME.'">
			<input type="hidden" name="PAYMENT_ID" value="'.$id.'">
			<input type="hidden" name="PAYMENT_AMOUNT" value="'.$amount.'">
			<input type="hidden" name="PAYMENT_UNITS" value="USD">
			<input type="hidden" name="STATUS_URL" value="'.$STATUS_URL.'">
			<input type="hidden" name="PAYMENT_URL" value="'.$PAYMENT_URL.'">
			<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
			<input type="hidden" name="NOPAYMENT_URL" value="'.$NOPAYMENT_URL.'">
			<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
			<input type="hidden" name="SUGGESTED_MEMO" value="">
			<input name="PAYMENT_METHOD" type="submit" value="Go to the merchant" />
			</form>

		</body>
			';
			
		echo $inv;	
		return TRUE;
		
	}
 
 	//advanced cash invoice 
	function AC($id, $amount)
	{
		require __DIR__ . '/merchant/sci/'.__FUNCTION__.'.php';
		
		$inv = '
			<body onload="document.forms[0].submit()">

			<form  action="https://wallet.advcash.com/sci/" method="POST">
			<input type="hidden" name="ac_account_email" value="'.$ac_account_email.'">
			<input type="hidden" name="ac_sci_name" value="'.$ac_sci_name.'">
			<input type="hidden" name="ac_order_id" value="'.$id.'">
			<input type="hidden" name="ac_amount" value="'.$amount.'">
			<input type="hidden" name="ac_currency" value="USD">
 			<input type="submit" value="Go to the merchant" />
			</form>

			</body>
			';
			
		echo $inv;	
		return TRUE;
		
	}
	
	 //payeer invoice 
	function PY($id, $amount)
	{
		require __DIR__ . '/merchant/sci/'.__FUNCTION__.'.php';
		
			//$m_shop = '12345'; // id мерчанта			
			//$m_orderid = '1'; // номер счета в системе учета мерчанта
			//$m_amount = number_format(100, 2, '.', ''); // сумма счета с двумя знаками после точки
			//$m_curr = 'USD'; // валюта счета
			$m_orderid = $id;
			$m_amount = $amount;
			$m_curr = 'USD';
			
			
			
			$m_desc = base64_encode('invoice_'.$m_orderid); // описание счета, закод  base64
			//$m_key = 'Ваш секретный ключ';
			// Формируем массив для генерации подписи
			$arHash = array(
				$m_shop,
				$m_orderid,
				$m_amount,
				$m_curr,
				$m_desc
				);
			/*
			// Формируем массив дополнительных параметров
			$arParams = array(
			'success_url' => 'http://google.com/new_success_url',
			'fail_url' => 'http://google.com/new_fail_url',
			'status_url' => 'http://google.com/new_status_url',
			 // Формируем массив дополнительных полей
			'reference' => array(
			'var1' => '1',
			'var2' => '2',
			'var3' => '3',
			6
			'var4' => '4',
			'var5' => '5',
			),
			//'submerchant' => 'mail.com',
			);
			// Формируем ключ для шифрования
			$key = md5('Ключ для шифрования дополнительных параметров'.$m_orderid);
			// Шифруем дополнительные параметры
			$m_params = urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,
			$key, json_encode($arParams), MCRYPT_MODE_ECB)));
			// Добавляем параметры в массив для формирования подписи
			$arHash[] = $m_params;
			*/
			// Добавляем в массив для формирования подписи секретный ключ
			$arHash[] = $m_key;
			// Формируем подпись
			$sign = strtoupper(hash('sha256', implode(':', $arHash)));
			
	
		$inv = '
		<body onload="document.forms[0].submit()">
		    <form method="post" action="https://payeer.com/merchant/">		
			<input type="hidden" name="m_shop" value="'.$m_shop.'">
			<input type="hidden" name="m_orderid" value="'.$m_orderid.'">
			<input type="hidden" name="m_amount" value="'.$m_amount.'">
			<input type="hidden" name="m_curr" value="'.$m_curr.'">
			<input type="hidden" name="m_desc" value="'.$m_desc.'">
			<input type="hidden" name="m_sign" value="'.$sign.'">				
			<input type="submit" name="m_process" value="Go to the merchant" />			
			</form>
		</body>
		';
			
		echo $inv;	
		return TRUE;
		
	}
	function BTC($id, $amount, $wallet_to)
	{
		require __DIR__ . '/merchant/sci/'.__FUNCTION__.'.php';
		//$return_user = 'https://ai-invest.biz/merchant/BTC/check.php';
		$inv = '
		<body onload="document.forms[0].submit()">
			<form action="https://apibtc.com/merchant/invoice/?wallet='.$wallet_to.'" method="POST">
				<input type="hidden" name="amount" value="'.$amount.'">
				<input type="hidden" name="return_user" value="'.$return_user.'">
				<input type="submit" value="Go to the merchant">
			</form>
		</body>
		';
		
		
		echo $inv;	
		return TRUE;
		
	}
 
 