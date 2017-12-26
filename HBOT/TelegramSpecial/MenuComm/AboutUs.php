<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class AboutUs 
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
		require __DIR__ . '/../Locale/MenuComm/AboutUs/'.$lang.'.php';
	   
	   	
		$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $about;	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 

	
	}
	
	
}