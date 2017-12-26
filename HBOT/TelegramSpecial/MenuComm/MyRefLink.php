<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class MyRefLink
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
	private function getBotName()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`botname`
				FROM
				`adminset`";
		$botname = $db -> selectCell($sql);
		return $botname;
	}
	private function getSiteName()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`msite`
				FROM
				`adminset`";
		$site = $db -> selectCell($sql);
		return $site;
	}
	private function sendMessage()
	{		
	    $States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/MyRefLink/'.$lang.'.php';	   
	   	
		$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';
  	    $sendMessage->text = $s0 ."%0A<code>{$this ->getSiteName()}/?ref={$this->chat_id}</code> %0A{$s1} %0A <code>https://telegram.me/{$this ->getBotName()}/?start={$this->chat_id}</code>";
        $TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 
		
	}
	
}