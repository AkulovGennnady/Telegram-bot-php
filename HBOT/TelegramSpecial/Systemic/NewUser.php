<?php

namespace HBOT\TelegramSpecial\Systemic;
  
use HBOT\DB\Database;
use HBOT\TelegramSpecial\MenuComm\ChooseLang;

use HBOT\TelegramSpecial\MenuComm\ShowMainMenu;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

class NewUser 
{
	public  $chat_id = 0.0;
	public  $username = '';
	public  $upline_id= 0.0;
	
	function __construct($chat_id, $username, $upline_id)
	{
		$this -> chat_id = $chat_id;
		$this -> username = $username;
		$this -> upline_id = $upline_id;
		
		$this -> perform ();
	}
	
	public function perform ()
	{ 
		
			
		//if user not exists insert info in db
		if (!($this -> checkIfUserExists ()))
		{
			$this -> insertUserToDB();
			sleep(1);
			$this -> showHelloMes();		
			$ChooseLang  = new ChooseLang($this -> chat_id);
		}else 
		{
			$ShowMainMenu = new ShowMainMenu($this -> chat_id);
		}
	  	//  file_put_contents('WTF.txt', "NewUser * ", FILE_APPEND | LOCK_EX);	
		//show hello message not depending on that
	}
	//+
	public function checkIfUserExists ()
	{
		$db = Database::getDB();
		
		$chat_id = $this -> chat_id;
		$sql = "SELECT `id` 
				FROM `users`
				WHERE
				`chat_id` = ?";

		if ($db -> selectCell($sql, [$chat_id]))
			return 1;		
		return 0;				
	}
	//+
	public function insertUserToDB()
	{
		 
		$db = Database::getDB();
		
		$chat_id  = $this -> chat_id;
		$username = $this -> username;	
		
		$now = time();
		$sql = "INSERT INTO 
				`users`
				(`chat_id`,`username`, `time`)
				VALUES
				(?, ?, {$now})";
		
		$db -> query($sql, [$chat_id, $username]);
		
		$sql = "INSERT INTO 
				`ubalance`
				(`chat_id`)
				VALUES
				(?)";
		$db -> query($sql, [$chat_id]);
		
		$sql = "INSERT INTO 
				`wallets`
				(`chat_id`)
				VALUES
				(?)";
		$db -> query($sql, [$chat_id]);
		
		//upline_id MUST be numeric, it's chat_id of referer
		if ($this -> upline_id)
			if ($this -> validate())
				$this -> updateUpline();
		
	}
	
	
	private function validate()
	{
		//check if is valid chat_id
		if (!ctype_digit($this -> upline_id))
		{
			return 0;
		}
		//check if the user with chat_id exists
		if (!($this -> Exists()))
		{
			return 0;
		}
		//check if the  inputed chat_id = user chat_id
		if (($this -> isSelf()))
		{
			return 0;
		}

		return 1;	 
		
	}
	private function isSelf ()
	{
		if($this->chat_id == $this->upline_id)		
			return 1; 
		return 0;
	}
	
	private function Exists()
	{
		$id = 0.0;
		$db = Database::getDB();
		$sql = "SELECT  
				`id`
				FROM
				`users`
				WHERE 
				`chat_id` = ?";
		$id = $db -> selectCell($sql, [$this->upline_id]);
		return $id; 
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
		$query = $db -> query($sql, [$this -> upline_id, $this -> chat_id]);
	}
	
	//+
	public function showHelloMes()
	{	
		$sendMessage = new sendMessage();
		$sendMessage->chat_id =  $this -> chat_id; 
		$sendMessage->text = "Welcome to the world of financial stability!";		
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	} 
}
 