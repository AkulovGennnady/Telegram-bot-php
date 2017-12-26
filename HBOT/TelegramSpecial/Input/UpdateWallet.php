<?php
namespace HBOT\TelegramSpecial\Input;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

 
use HBOT\TelegramSpecial\Input\Error;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class UpdateWallet
{
	private $wallet;
	private $curComm;
	private $chat_id;
	
		
	function __construct($wallet, $curComm, $chat_id) 
	{	
		$this -> wallet = $wallet;
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
			$this -> updateWt();
			$this -> sendMessage();
		}
	}
	
	private function validate()
	{
		switch ($this -> wallet){
			case 'PM':
				//check if wallet is valid 
				if (preg_match('/(^[U]{1}[0-9]{7,8}$)/', $this -> curComm))
				{					
					return 1;
				}else 
				{
					$this -> Error($this -> wallet);
					return 0;
				}
			case 'AC':
				//check if wallet is valid (e-mail validaion)
				if (filter_var($this -> curComm, FILTER_VALIDATE_EMAIL))
				{					
					return 1;
				}else 
				{
					$this -> Error($this -> wallet);
					return 0;
				}
			case 'PY':
				//check if wallet is valid 
				if (preg_match('/(^[P]{1}[0-9]{7,8}$)/', $this -> curComm))
				{					
					return 1;
				}else 
				{
					$this -> Error($this -> wallet);
					return 0;
				}
			case 'BTC':
				//check if wallet is valid 
				if (preg_match('/(^(?=[1-9])(?=[a-zA-Z0-9])[a-zA-Z0-9]+[^0OIl]{25,34}$)/', $this -> curComm))
				{					
					return 1;
				}else 
				{
					$this -> Error($this -> wallet);
					return 0;
				}
		}
	}

			
	private function Error($erN)
	{		
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		require __DIR__ .'/../Locale/Input/UpdateWallet/'. $lang .'.php';	
		
		$Error = new Error(${$erN}, $this -> chat_id);
	}
			
			
	private function setState()
	{
		$States = new States($this -> chat_id);
		//SET STATE TO USER!!!
		$state = $States -> setState('menu');
	}
	private function updateWt()
	{
		$db = Database::getDB();
		$sql = "UPDATE 
				`wallets`
				SET 
				`{$this->wallet}` = ?
				WHERE 
				`chat_id` = ?";
		$query = $db -> query($sql, [$this -> curComm, $this -> chat_id]);
	}
	
	private function sendMessage()
	{
			$States = new States($this -> chat_id);
			$lang = $States -> getLang();		
			//locale file of a class s0, s1....
		    require __DIR__ .'/../Locale/Input/UpdateWallet/'. $lang .'.php';
			
			$sendMessage = new sendMessage();
			$sendMessage->chat_id = $this -> chat_id;	
			
			$sendMessage->text = $s0;
			$TelegramMain = new TelegramMain();
			$TelegramMain -> performApiRequest($sendMessage);	
	}
		
}