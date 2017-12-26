<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class ChangeMyWallet
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
		$States = new States($this -> chat_id);
		//SET STATE TO USER!!!
		//PM, AC, PY, BTC
		$state = $States -> setState($this -> curComm[1]);
		
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/InlineComm/ChangeMyWallet/'. $lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = ${$this->curComm[1]};
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);	
	}
	
	
}