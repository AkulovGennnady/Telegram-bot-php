<?php
namespace HBOT\TelegramSpecial;

//use TelegramSpecial\InlineComm\ChangeMyLanguage;
//use ALL from InlineComm!!!
//in className!!!!! BECUAUE NAMESPACES are used!
/*
use TelegramSpecial\InlineComm\ChangeMyWallet;
use TelegramSpecial\InlineComm\SetMyUpline;
use TelegramSpecial\InlineComm\WithdawMyFunds;

use TelegramSpecial\InlineComm\ChoosePhan;
use TelegramSpecial\InlineComm\ChooseSum;
use TelegramSpecial\InlineComm\GenerPayment;
*/

class InlineCommExec 
{
	private $curComm;
	private $chat_id;
	private $TextCommNS = "HBOT\\TelegramSpecial\\InlineComm\\";
	
	function __construct($curComm, $chat_id) 
	{	
		$this -> chat_id = $chat_id;
		//curComm[0] - command, [1]..[n] - parameters 
		$this -> curComm = explode(" ", $curComm);		 
		$this -> execute();
	}
 	//creates a new class of a command
	private function execute()
	{
		 $Class = $this -> TextCommNS;//namespace
		 if ($ClassFind = $this -> findInlineComm())
		 {
			$Class .= $ClassFind;
			$newCommExec = new $Class($this -> curComm, $this -> chat_id);
		 }else 
		 {
			//INLINE COMMAND NOT FOUND
		 }

	}
	private function findInlineComm()
	{
		require_once __DIR__ .'/Dictionary/InlineComm.php';
		
		$inlineClassName = $inlineComm[$this -> curComm[0]];	
			return $inlineClassName;		
		return 0;
	}
}