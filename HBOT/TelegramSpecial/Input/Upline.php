<?php
namespace HBOT\TelegramSpecial\Input;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramSpecial\Input\Error;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class Upline
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
		//$this -> setState();
		
		if ($this -> validate())
		{
			$this -> setState();
			$this -> updateUpline();
			$this -> sendMessage();
		}
	}
	
	private function validate()
	{
		//check if is valid chat_id
		if (!ctype_digit($this -> curComm))
		{
			$this -> Error('er1');
			return 0;
		}
		//check if the user with chat_id exists
		if (!($this -> Exists()))
		{
			$this -> Error('er2');
			return 0;
		}
		//check if the  inputed chat_id = user chat_id
		if (($this -> isSelf()))
		{
			$this -> Error('er3');
			return 0;
		}
		return 1;	 	
	}
	private function isSelf ()
	{
		if($this->chat_id == $this->curComm)	
			return 1; 
		return 0;
	}
	private function Exists()
	{
		$db = Database::getDB();
		$sql = "SELECT  
				`id`
				FROM
				`users`
				WHERE 
				`chat_id` = ?";
		$id = $db -> selectCell($sql, [$this->curComm]);
		return $id; 
	}
			
	private function Error($erN)
	{		
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();
		
		require __DIR__ .'/../Locale/Input/Upline/'. $lang .'.php';	
		$Error = new Error($$erN, $this -> chat_id);
	}
			
			
	private function setState()
	{
		$States = new States($this -> chat_id);
		//SET STATE TO USER!!!
		$state = $States -> setState('menu');
	}
	private function updateUpline()
	{
		$db = Database::getDB();
		$sql = "UPDATE 
				`users`
				SET 
				`upline_id` = ?
				WHERE 
				`chat_id` = ?";
		$query = $db -> query($sql, [$this -> curComm, $this -> chat_id]);
	}
	
	private function sendMessage()
	{
			$States = new States($this -> chat_id);
			$lang = $States -> getLang();		
			//locale file of a class s0, s1....
			require __DIR__ .'/../Locale/Input/Upline/'. $lang .'.php';
			
			$sendMessage = new sendMessage();
			$sendMessage->chat_id = $this -> chat_id;	
			$sendMessage->text = $s0;
			$TelegramMain = new TelegramMain();
			$TelegramMain -> performApiRequest($sendMessage);	
	}
		
}