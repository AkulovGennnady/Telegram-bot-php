<?php

namespace HBOT\Funds;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;
 
use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage; 

//AC class...
use HBOT\Funds\AC\MerchantWebService;
use HBOT\Funds\AC\authDTO;
use HBOT\Funds\AC\sendMoneyRequest;
use HBOT\Funds\AC\validationSendMoney;
use HBOT\Funds\AC\sendMoney;

//PY class
use HBOT\Funds\PY\CPayeer;

class WithFunds
{
	private $paym_sys;
	private $amount;	
 	private $chat_id;
	
	private $trans_id;	
	private $comm;	
	
	function __construct($paym_sys, $amount, $chat_id) 
	{
		$this -> paym_sys = $paym_sys;
 		$this -> amount = $amount;		
		$this -> chat_id = $chat_id;
		$this -> setLang();
		
		$this -> execute();
	}
 
	private function execute()
	{	
		if ($this -> check())
		{
			$this -> updBal();
			//payment system method/class
			//if ok - send message with ok
			//else - send message with error ()
			//$this -> updWBal();
			//with_req
			if ($this -> tryWith() )
			{
				$this -> updWBal();
				$this -> insWithReq();
				$this -> okMessage();
			}else
			{
				$this -> backBal();
				$this -> errorMessage();
			}
		}	
	}
	
 	private function updBal()
	{
		$db = Database::getDB();
		$sql = "UPDATE
				`ubalance`
				SET
				`bal` = `bal` - '{$this -> amount}'
				WHERE 
				`chat_id` = ?";
		$db -> query($sql, [$this -> chat_id]);
	} 
	
	private function backBal()
	{
		$db = Database::getDB();
		$sql = "UPDATE
				`ubalance`
				SET
				`bal` = `bal` + '{$this -> amount}'
				WHERE 
				`chat_id` = ?";
		$db -> query($sql, [$this -> chat_id]);
	} 
	
	private function updWBal()
	{
		$db = Database::getDB();
		$sql = "UPDATE
				`ubalance`
				SET
				`wbal` = `wbal` + '{$this -> amount}'
				WHERE 
				`chat_id` = ?";
		$db -> query($sql, [$this -> chat_id]);
	} 
	private function insWithReq()
	{
		$db = Database::getDB();
		$now = time();
		$sql = "INSERT INTO
				`with_req`
				(`chat_id`,`amount`, `comm`, `paym_sys`, `trans_id`, `time`, `status`)
				VALUES
				(?, '{$this -> amount}', ?, '{$this -> paym_sys}', ?, '{$now}', '1')";
		$ins = $db -> query($sql, [$this -> chat_id, $this -> comm, $this->trans_id]);
	}
		
	private function check()
	{
		if($this -> amount  >  $this -> getBalance())
		{
			$moreThanBalance = $this -> moreThanBalance();
			return 0;		
		}else
		{	
			//Right amount
			return 1;
		}		
	}
	
	private function getBalance()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`bal`
				FROM
				`ubalance`
				WHERE
				`chat_id` = ?";
		$bal = $db -> selectCell($sql, [$this -> chat_id]);
		return $bal;
	}
	
	private function setLang()
	{
		$States = new States($this -> chat_id);
		$lang = $States -> getLang();		
		$this -> lang = $lang; 
	}
 	
	private function moreThanBalance()
	{
		require __DIR__ .'/../TelegramSpecial/Locale/Funds/WithFunds/'. $this -> lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';	
		$sendMessage->text = $er1;		
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
 	private function okMessage()
	{
		require __DIR__ .'/../TelegramSpecial/Locale/Funds/WithFunds/'. $this -> lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';		
		$sendMessage->text = "{$s0} {$s1} {$this -> trans_id}";	 			
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	private function  errorMessage()
	{
		require __DIR__ .'/../TelegramSpecial/Locale/Funds/WithFunds/'. $this -> lang .'.php';

		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';		
		$sendMessage->text = $er2;	 			
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
	private function tryWith()
	{
		switch ($this -> paym_sys){
			case 'AC':
				$resp = $this -> sendAC();			
				break;
			case 'PM':
				$resp = $this -> sendPM();			
				break;
			case 'PY':
				$resp = $this -> sendPY();			
				break;	
			case 'BTC':
				$resp = $this -> sendBTC();			
				break;		
		}
		return $resp;
	}
	private function getWallet()
	{
		$db = Database::getDB();
		$sql = "SELECT
				'{$this->paym_sys}'
				FROM
				`wallets`
				WHERE
				`chat_id` = ?";
		$wallet = $db -> selectCell($sql, [$this -> chat_id]);
		return $wallet;
	}
	private function sendAC()
	{
		//return 0;
		//require __DIR__ . '/AC/MerchantWebService.php';
		require __DIR__ . '/api/AC.php';

			//error_reporting(E_ALL);
			//ini_set('display_errors', '1');
			ini_set('max_execution_time', 0);
			//require_once("MerchantWebService.php");
			$merchantWebService = new MerchantWebService();

			$arg0 = new authDTO();
			$arg0->apiName = $apiName;
			$arg0->accountEmail = $accountEmail;
			$arg0->authenticationToken = $merchantWebService->getAuthenticationToken($api_password);

			$arg1 = new sendMoneyRequest();
			$arg1->amount = $this -> amount;
			$arg1->currency = "USD";
			$arg1->email = $this -> getWallet();
			//$arg1->walletId = "U000000000000";
			$arg1->note = $this -> chat_id;
			$arg1->savePaymentTemplate = false;

			$validationSendMoney = new validationSendMoney();
			$validationSendMoney->arg0 = $arg0;
			$validationSendMoney->arg1 = $arg1;

			$sendMoney = new sendMoney();
			$sendMoney->arg0 = $arg0;
			$sendMoney->arg1 = $arg1;

 //block try returns function
			try {
				$merchantWebService->validationSendMoney($validationSendMoney);			 
				$sendMoneyResponse = $merchantWebService->sendMoney($sendMoney);

				//echo print_r($sendMoneyResponse, true)."<br/><br/>";	
				//echo $sendMoneyResponse->return."<br/><br/>";
			} catch ( \Exception $e) {
				//echo "ERROR MESSAGE => " . $e->getMessage() . "<br/>";
			    //echo $e->getTraceAsString();
				
				//SOME ERROR MESSAGE TO ADMIN				
				$Logger = new \Logger('WithFundsAC.txt');
				$Logger -> log($e->getMessage(), $e->getTraceAsString());				
				return 0; 
			}
			
			echo "<br/> OK <br/>";			
			$this -> trans_id = $sendMoneyResponse->return;
			//comission 0.0% 	
			$this -> comm = 0.0;
			return 1;

	}
	private function sendPM()
	{

		//require __DIR__ . '/AC/MerchantWebService.php';
		require __DIR__ . '/api/PM.php';
		
		$Payee_Account = $this -> getWallet();		
		$now = time();
		
		$url = "https://perfectmoney.is/acct/confirm.asp?AccountID={$AccountID}&PassPhrase={$PassPhrase}&Payer_Account={$Payer_Account}&Payee_Account={$Payee_Account}&Amount={$this->amount}&PAY_IN=1&PAYMENT_ID={$now}";
		
		//curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);

		if($data===false){
		   //echo 'error openning url';
		   	$Logger = new \Logger('WithFundsPM.txt');
			$Logger -> log('Error openning url');
		   //some message?
		   return 0;
		}
		// searching for hidden fields
		if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $data, $result, PREG_SET_ORDER)){
		   //echo 'Ivalid output';
		   	$Logger = new \Logger('WithFundsPM.txt');
			$Logger -> log('Ivalid output', $data);
		   return 0;
		}
		
		foreach($result as $item){
			   $key  = $item[1];
		 $resp[$key] = $item[2];
		}

		/* echo '<pre>';
		print_r($resp);
		echo '</pre>'; */		
		
		if($resp['ERROR'])
		{		
			//SOME ERROR MESSAGE TO ADMIN
			//send email with $resp['ERROR']
			$Logger = new \Logger('WithFundsPM.txt');
			$Logger -> log('ERROR', $resp['ERROR']);
			return 0;
		}else
		{
			$this -> trans_id = $resp['PAYMENT_BATCH_NUM'];
			//comission 0.5% (verified account)	
			$this -> comm = $this->amount * 0.005;
			return 1;
		}
	}
	private function sendPY()
	{
		
		//require __DIR__ . '/AC/MerchantWebService.php';
		require __DIR__ . '/api/PY.php';
		
		//require_once('cpayeer.php');
/* 		$accountNumber = '';
		$apiId = '';
		$apiKey = '****************'; */
		$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
		if ($payeer->isAuth())
		{
			$initOutput = $payeer->initOutput(array(
				'ps' => '1136053',
				//'sumIn' => 1,
				'curIn' => 'USD',
				'sumOut' => $this -> amount,
				'curOut' => 'USD',
				'param_ACCOUNT_NUMBER' => $this -> getWallet()
			));

			if ($initOutput)
			{
				$historyId = $payeer->output();
				if ($historyId > 0)
				{
					echo "Выплата успешна";				
					//set trans_id
					$this -> trans_id = $historyId;
					//comission 0.95% 	
			        $this -> comm = $this->amount * 0.0095;
					return 1;				
				}
				else
				{
					//echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
					$Logger = new \Logger('WithFundsPY.txt');
					$Logger -> log(implode($payeer->getErrors()));
					return 0;
				}
			}
			else
			{
				//echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
				$Logger = new \Logger('WithFundsPY.txt');
				$Logger -> log(implode($payeer->getErrors()));
				return 0;
			}
		}
		else
		{
			//echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
			$Logger = new \Logger('WithFundsPY.txt');
			$Logger -> log(implode($payeer->getErrors()));			
			return 0;
		}
		return 0;
	}	
	
	private function sendBTC()
	{
		
		require __DIR__ . '/api/BTC.php';	
		$wallet = $this -> getWallet();
		
		//CONVERT TO BTC!!!!!!!!!!!!!!!!
		$amount = $this -> USDtoBTC($this->amount);
		$amount = round($amount, 8);
		
		$url = "https://apibtc.com/api/sendmoney/?token={$token}&wallet={$wallet}&amount={$amount}";
			
		//curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
		curl_setopt($ch, CURLOPT_URL, $url);
		$json = curl_exec($ch);
		curl_close($ch);
		//curl
				
		if(!$json){
		   //echo 'error openning url';
		   	$Logger = new \Logger('WithFundsBTC.txt');
			$Logger -> log('Error openning url');
		     return 0;
		}		
		//resonse is json to array
		$data = json_decode($json, TRUE);			
		
		if ($data['success'] == false){			
			//NOT ENAUGH FUNDS
		   	$Logger = new \Logger('WithFundsBTC.txt');
			$Logger -> log($json);
		     return 0;			
		} else {
			
			$this -> trans_id = $data['Res']['tx'];
			//comission			
			$this -> comm = $this -> BTCtoUSD($data['Res']['commission']);
			//$data['Res']['commission']
			//$data['Res']['balance']
			return 1;
		}
		return 0;

	}
	private function BTCtoUSD($inBTC)
	{
		$db = Database::getDB();
		//get course
		$sql = "SELECT `cBTC` FROM `adminset`";
		$cBTC = $db -> selectCell($sql);	
		//value in USD				
		$USD = $inbtc * $cBTC;
		return $USD;
	}
	private function USDtoBTC($inUSD)
	{
		$db = Database::getDB();
		//get course
		$sql = "SELECT `cBTC` FROM `adminset`";
		$cBTC = $db -> selectCell($sql);	
		//value in BTC				
		$BTC = $inUSD / $cBTC;
		return $BTC;
	}
	
	
}

 

 
