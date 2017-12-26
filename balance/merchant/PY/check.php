<?php
//check Payeer payments
use balance\merchant\classes\NDUSD;
require_once __DIR__ .'/../../../autoload.php';

require_once __DIR__ . '/../sci/PY.php';
		
		//IP check
		if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210')))
			return 0;

		if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
		{
			//$m_key = 'Ваш секретный ключ';

			$arHash = array(
				$_POST['m_operation_id'],
				$_POST['m_operation_ps'],
				$_POST['m_operation_date'],
				$_POST['m_operation_pay_date'],
				$_POST['m_shop'],
				$_POST['m_orderid'],
				$_POST['m_amount'],
				$_POST['m_curr'],
				$_POST['m_desc'],
				$_POST['m_status']
			);

			if (isset($_POST['m_params']))
			{
				$arHash[] = $_POST['m_params'];
			}

			$arHash[] = $m_key;

			$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

			if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success' )
			{
				//valid payment		
				echo $_POST['m_orderid'].'|success';				
				$ND = new NDUSD($_POST['m_operation_id'], $_POST['m_orderid']);					
				//exit;
			} 

			echo $_POST['m_orderid'].'|error';			
			$Logger = new \Logger('checkPY.txt');
			$Logger -> log('REASON: fake data', "POST: ".serialize($_POST)."; STRING: $string; HASH: $hash");
			return 0;
		}
?>