<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class MyUpline
{
	 
	private $chat_id;
	
		
	function __construct($chat_id) 
	{	
 		$this -> chat_id = $chat_id;		
		$this -> perform();			
	}
	private function perform()
	{
		$this ->sendMessage();
	}
	private function getUplineId()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`upline_id`
				FROM
				`users`
				WHERE
				`chat_id` =	?";
		$upline_id = $db -> selectCell($sql, [$this->chat_id]);
		return $upline_id;
	}
	private function getUplineUsn()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`username`
				FROM
				`users`
				WHERE
				`chat_id` =	'{$this->getUplineId()}'";
		$username = $db -> selectCell($sql);
		return $username;
	}
	private function sendMessage()
	{		
	    $States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/MyUpline/'.$lang.'.php';	   
	   	
		$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';
		
		if ($this ->getUplineId())
			$sendMessage->text = $s0 ."<b>{$this ->getUplineId()} ({$this ->getUplineUsn()})</b>";
		else 		
			$sendMessage->text = $s1; 
		
        $TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 
		
	}
	
	
	
}