<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;
use HBOT\TelegramSpecial\MenuComm\ShowMainMenu;

class ChangeMyLanguage 
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
		//set Language
		$States -> setLang($this -> curComm[1]);
		
	   //and show main menu	   
		$ShowMainMenu = new ShowMainMenu($this -> chat_id);	
	}	
}