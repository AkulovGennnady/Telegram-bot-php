<?php
namespace HBOT\TelegramSpecial\Systemic;
use HBOT\DB\Database;
 
class PotenPay 
{
	private $chat_id;
	private $curComm;
	private $descr;
 	private $site;
	private $url;
	private $pay; 
	
	function __construct($curComm, $chat_id)
	{
		$this -> curComm = $curComm;
		$this -> chat_id = $chat_id;
		$this -> url = '/balance/addfunds.php?descr=';		
		
		$this -> perform ();
	}
	
	private function perform ()
	{ 
	    $this -> setSiteName();
 	 	$this -> descrCreate(); 
		$this ->insertPotenPayToDB();				
	}
	//+
	private function descrCreate ()
	{
		$param = implode(' ',$this -> curComm); 
		$this -> descr = sha1($param . time());			
	}
	public function getURL ()
	{
		return $this -> site . $this -> url . $this -> descr . $this -> pay;			
	}
	
	private function setSiteName ()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`msite`
				FROM
				`adminset`";
		$site = $db -> selectCell($sql);
		$this -> site = $site;		
	}
	//+
	private function insertPotenPayToDB()
	{
		$db = Database::getDB();		

		$chat_id  = $this -> chat_id;
		$paym_sys = $this -> curComm[1];
		$plan_id  = $this -> curComm[3];
		$amount   = $this -> curComm[5];
		$descr    = $this -> descr;
		$time     = time();
		
		switch ($paym_sys){
			case 'AC':
			case 'PM':
			case 'PY':			
				$this -> pay = '&pay=' . $paym_sys;
				$sql = "INSERT INTO `poten_usd`
						(`chat_id`,`plan_id`,`amount`,`paym_sys`,`descr`,`time`)
						VALUES
						(?, ?, ?, ?, ?, ?)";	
				$ins_id = $db -> query($sql, [$chat_id, $plan_id, $amount, $paym_sys, $descr, $time]);
				
				break;
			
			case 'BTC':
				$this -> pay = '&pay=' . $paym_sys;
				//set poten pay
				$descr = $this -> descr;
				$sql = "INSERT INTO `poten_BTC`
						(`chat_id`,`plan_id`, `descr`,`time`)
						VALUES
						(?, ?, ?, ?)";	
				$ins_id = $db -> query($sql, [$chat_id, $plan_id, $descr, $time]);
			
				//get course
				$sql = "SELECT `cBTC` FROM `adminset`";
				$cBTC = $db -> selectCell($sql);	
				//value in BTC				
				$amount = $amount / $cBTC; //15 / 1500 = 0.01
				//wallet_to send				
				$wallet_to = $this -> generBTCWallet($ins_id);
				
				$sql = "UPDATE `poten_BTC`
						SET
						`amount` = '{$amount}',
 						`wallet_to` = '{$wallet_to}'					
						WHERE `id` = '{$ins_id}'";			
				$upd = $db -> query($sql);				
				break;
			
		}
		
	}
	private function generBTCWallet($ins_id)
	{	
		//!!!!!!!
		require __DIR__ . '/../../Funds/sci/BTC.php';
		
		$payment_id = $ins_id;//$this -> descr;
		//$hash = md5($payment_id."YOUR UNIQUE HASH"); //---?????
		$my_callback_url = urlencode($return_url."?ID=".$payment_id."&");

		$url = "https://apibtc.com/api/create_wallet/?token={$token}&callback=".$my_callback_url;
		
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($handle, CURLOPT_TIMEOUT, 60);		
		$json = curl_exec($handle);			
		//$object = json_decode($response);		
		//$json = file_get_contents($url);
		$res = json_decode($json, true);
		
		if($res["success"]){
			$sign = md5($res["Res"]["Address"].$access_key);
			if($sign == $res["Res"]["Sign"]){
				//Send the form 
				return $res["Res"]["Address"]; //- Wallet for the user to transfer money 
			}
			//log?
		}			
		//return $address;
	}
	
}
 