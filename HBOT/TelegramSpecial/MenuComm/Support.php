<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class Support 
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
		require __DIR__ . '/../Locale/MenuComm/Support/'.$lang.'.php';
		//load support contacts
		require __DIR__ . '/../../config/support.php';	   
	   	
		$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = "{$s0} %0A {$sup[0]} %0A {$sup[1]} %0A {$sup[2]} %0A {$sup[3]}";	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 	
	}	
}