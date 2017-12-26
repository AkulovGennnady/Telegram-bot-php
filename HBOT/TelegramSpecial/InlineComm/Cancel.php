<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class Cancel
{
	private $curComm;
	private $chat_id;
	
		
	function __construct($curComm, $chat_id) 
	{	
		$this -> curComm = $curComm;
		$this -> chat_id = $chat_id;
		
		$this -> perform();			
	}
	private function perform()
	{
		$this -> setState();		
		
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/InlineComm/Cancel/'. $lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);	
	}
	
	private function setState()
	{
		$States = new States($this -> chat_id);
		//SET STATE TO USER!!!
		$state = $States -> setState('menu');
	}
	
	
	
}