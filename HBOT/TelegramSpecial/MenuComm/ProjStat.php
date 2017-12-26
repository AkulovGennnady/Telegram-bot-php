<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

class ProjStat 
{
	 
	private $chat_id;
	
		
	function __construct($chat_id) 
	{	
 		$this -> chat_id = $chat_id;		
		$this -> perform();			
	}
	private function perform()
	{
		$States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);
		
		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/ProjStat/'.$lang.'.php';	
		
		
		$db = Database::getDB();
		
		//is active (days)
		$sql = "SELECT `beg_date` FROM `adminset`";
		$timest = $db -> selectCell($sql);
		//timestamp to days
		echo $timest."<br>";
		$days =  floor ( (time() - $timest) / 86400 );
	   	
		//participants in mission
		$sql = "SELECT COUNT(*) FROM `users`";
		$part = $db -> selectCell($sql);
		
		//total invested
		$sql = "SELECT SUM(`amount`) FROM `depos`";
		if ( ! $inv = $db -> selectCell($sql))
			$inv = 0;
		
		//total withdrawn
		$sql = "SELECT SUM(`wbal`) FROM `ubalance`";
		$with = $db -> selectCell($sql);
		
  
		$text = "$s0<b>{$days}</b> %0A $s1 <b>{$part}</b> %0A {$s2} <b>{$inv} USD</b> %0A {$s3} <b>{$with} USD</b>";
		
		$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $text;	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 

	
	}
	
	
}