<?php
namespace HBOT\TelegramSpecial;
use HBOT\TelegramSpecial\Systemic\States;
use HBOT\TelegramSpecial\TextCommExec;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

 
class MenuCommExec 
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
		$Class = $this -> MenuCommNS;//namespace
		if ($ClassFind = $this -> findMenuComm())
		{
			$Class .= $ClassFind;
			$newCommExec = new $Class($this -> chat_id);
		}
		else 
		{
			//NOT MENU COMMANDS (text)
			$TextCommExec = new TextCommExec($this -> curComm, $this -> chat_id);
		}

	}
	private function findMenuComm()
	{
		$ind = $this -> findIndexLocale();
		if ($ind !== FALSE)
		{
			require_once __DIR__ .'/Dictionary/MenuComm.php';		
			$menuClassName = $menuComm[$ind];	
			return $menuClassName;		
		} else 
			return '';
		
	}
	
	private function findIndexLocale()
	{
		//get lang of a user
		$States = new States($this -> chat_id );
		$lang = $States -> getLang();
			
		//include locale file
		//reqiire_once not working
		require __DIR__ .'/Locale/Menu/'. $lang .'.php';
		 
		//find index in locale file 
		$ind = NULL;
		foreach ($menu as $key => $val)
		{
			if ($val == $this -> curComm)
			{
				$ind = $key;
			}			
		}
		
		if ($ind !== NULL)
			return $ind;
		else 
			return false;
	}
	
}

 
