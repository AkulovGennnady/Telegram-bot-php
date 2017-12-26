<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

use HBOT\TelegramSpecial\Input\Error;

class WithState
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
	 	if ( $this->checkBal() > 0)
		{
			$this -> setState();	
			$this -> okMessage();		
		}
		else
		{
			$this -> NullBall();
		}
 	
	}	
	
	private function checkBal()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`bal`
				FROM
				`ubalance`
				WHERE
				`chat_id` = ?";
		$bal = $db -> selectCell($sql, [$this -> chat_id]);
		return $bal;
	}
	
	private function setState()
	{
		$States = new States($this -> chat_id);
		//SET STATE TO USER!!!
		$state = $States -> setState($this -> curComm[0].' '.$this -> curComm[1]); //with AC
	}
	private function okMessage()
	{
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/InlineComm/WithState/'. $lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	private function NullBall()
	{		
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();
		
		require __DIR__ .'/../Locale/InlineComm/WithState/'. $lang .'.php';		
		//$Error = new Error($er1, $this -> chat_id);		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $er1;
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
	
	
}