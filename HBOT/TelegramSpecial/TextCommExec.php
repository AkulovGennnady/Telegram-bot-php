<?php
namespace HBOT\TelegramSpecial;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

 
class TextCommExec 
{
	private $curComm;
	private $chat_id;
 	private $MenuCommNS = "HBOT\\TelegramSpecial\\MenuComm\\";
	
	function __construct($curComm, $chat_id) 
	{		
		$this -> curComm = $curComm;		
		$this -> chat_id = $chat_id;
		//curComm - text command
		$this -> execute();
	}
 	//creates a new class of a command
	private function execute()
	{	
		$this -> caseWord();
	}
	private function answer($s0)
	{
		$sendMessage = new SendMessage();
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->text = $s0;
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 
	}
	private function caseWord()
	{
		$mess = strtolower ($this -> curComm);
		switch ($mess)
		{
			//text messages from user handle here
			/*case 'some text':
				$this -> answer('some answer');
				break;
			*/

			
		}
		
	}
	
}

 
