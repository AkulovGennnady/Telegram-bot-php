<?php

namespace balance\merchant\classes;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;
use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

//require __DIR__ .'/../../../autoload.php';

class NDUSD
{

	private $trans_id;
	private $id;
	
 	private $chat_id;
	private $amount;
	
	private $depo_id;

	function __construct($trans_id = '', $id = 0)
	{
		if (!$trans_id && !$id)
			die();
		$this -> trans_id = $trans_id;
		$this -> id = $id; //pot pay id
		$this -> perform();
		
	}
	private function perform()
	{
		if ($this -> isCreated()) die();
		if ($this -> exPotPay()) 
		{	
			$this -> updPotPay();
			if ($this -> nDepo())		
				$this -> refCom();
		}
	}
	//+
	private function updPotPay()
	{
		//update poten pay table
		$db = Database::getDB();
		$sql = "UPDATE  
				`poten_usd` 
				SET
				`trans_id` = ?, `status` = '1'
				WHERE 
				`id` = ?";
		 $db -> query($sql, [$this -> trans_id, $this -> id]);
		 return 1;
	}		
	//+
	private function nDepo()
	{
	 
		//0 - select info from poten pay
		$db = Database::getDB();
		$sql = "SELECT  
				`chat_id`, `plan_id`, `amount`, `paym_sys`
				FROM `poten_usd`
				WHERE  `id` =  ?";
		$row = $db -> selectRow($sql, [$this ->id]);
 		
		//set
		$this -> chat_id = $row['chat_id'];
		$this -> amount  = $row['amount'];
		
		//1-create 
		$now = time();//timestamp now 		
		$sql = "INSERT INTO
				`depos` 
				(`chat_id`,`pot_id`, `plan_id`, `amount`, `paym_sys`,  `time`, `lpt`)		
				VALUES
				('{$row['chat_id']}', '{$this -> id}', '{$row['plan_id']}', '{$row['amount']}',
				'{$row['paym_sys']}', '{$now}', '{$now}')";
		$this ->depo_id = $db -> query($sql);
		
		$this -> nDepoNotif($this -> chat_id, $this -> amount, $this ->depo_id);
		return 1;
	}
	//
	private function refCom()
	{
		$db = Database::getDB();
		//0
		//get upline_id
		$sql = "SELECT  
				`upline_id`
				FROM `users`
				WHERE  `chat_id` =  ?";
		$upline_id = $db -> selectCell($sql, [$this -> chat_id]);
		//1
		//ref%
		//if upline_id is set
		if($upline_id)
		{
			//1 - pers of ref comission of 1 level
			$sql = "SELECT  
					`ref1`
					FROM `adminset`";
			$ref1 = $db -> selectCell($sql);
			
			//2 - add ref comission to balance (USD)
			$refcom =  $this -> amount * $ref1; //like 10 * 0.05
			$refcom  = round($refcom, 2);//0.459 = 0.46
			//3 - add ref comission to balance
			$sql = "UPDATE  
					`ubalance` 
					SET
					`bal` = `bal` + '{$refcom}'
					WHERE 
					`chat_id` = '{$upline_id}'";
			$db -> query($sql);			
			/*`ref_com` = `ref_com` +  '{ $refcom }'*/
			
			//4 - paid ref comission  to table
			$now = time();//timestamp now 
			$sql = "INSERT INTO 
					`ref_paid` 
					(`chat_id`,`depo_id`, `amount`, `time`)		
					VALUES
					('{$upline_id}', '{$this->depo_id}', '{$refcom}', '{$now}')
					";
			$db -> query($sql);		
		}
		//send notification about referal commission
		$this -> refComNotif($upline_id, $refcom);		
	}
	
	private function exPotPay()
	{
		$db = Database::getDB();
		//check if depo is already made
		$sql = "SELECT  
				`amount`
				FROM 
				`poten_usd`
				WHERE 
				`id` = ?";		 
	    $amount = $db -> selectCell($sql,[$this ->id]);
		//if amount (>0)(poten pay exists)
		return $amount;
 	}
	
	private function isCreated()
	{
		$id = 0;
		$db = Database::getDB();
		//check if depo is already made
		$sql = "SELECT  
				`status`
				FROM 
				`poten_usd`
				WHERE 
				`trans_id` = ?";		 
	    $id = $db -> selectCell($sql,[$this ->trans_id]);
		//if id (>0)(depo already exists)			
		return $id;	
	}
	//++
	private function nDepoNotif($chat_id, $amount, $depo_id)
	{
		//locale file
		$States = new States($chat_id);
		$lang = $States -> getLang();
		require_once __DIR__ .'/../../../Locale/ND/'.$lang.'.php';
		//sending message	
		$sendMessage = new SendMessage(); 
		$TelegramMain = new TelegramMain(); 
		$sendMessage->chat_id = $chat_id;
		$sendMessage->parse_mode = 'HTML';		
		$sendMessage->text = "{$s0} <b>{$amount}</b> {$s1} <b>{$depo_id}</b>"; 
		$TelegramMain -> performApiRequest($sendMessage); 
	}
	//++
	private function refComNotif($chat_id, $amount)
	{		
		//locale file
		$States = new States($chat_id);
		$lang = $States -> getLang();
		require_once __DIR__ .'/../../../Locale/RC/'.$lang.'.php';
		//sending message	
		$sendMessage = new SendMessage(); 
		$TelegramMain = new TelegramMain(); 
		$sendMessage->chat_id = $chat_id;
		$sendMessage->parse_mode = 'HTML';		
		$sendMessage->text = "{$s0} <b>{$amount}</b> {$s1}"; 
		$TelegramMain -> performApiRequest($sendMessage); 
	}
	
			
}

/*
require __DIR__ . '/../../../HBOT/DB/Database.php';

$trans = '123456789'; //batch_num
$id = '7'; //id in poten pay

$ND = new ND($trans, $id);
*/