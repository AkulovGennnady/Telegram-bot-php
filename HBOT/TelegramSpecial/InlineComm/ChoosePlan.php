<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\DB\Database;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class ChoosePlan 
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
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/InlineComm/ChoosePlan/'. $lang .'.php';
		
		//amount 10
		$comm = $this -> curComm[0].' '.$this -> curComm[1];
		
		//plan info
		$db = Database::getDB();
		$sql = "SELECT `id`,`name` FROM `plans`";
		$row = $db -> select($sql);
		//row - array of assos arrays
		
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		
		[ new InlineKeyboardButton($row[0]['name'], '', 'plan '.$row[0]['id'].' '.$comm) ]
		//,[ new InlineKeyboardButton($row[1]['name'], '', 'plan '.$row[1]['id'].' '.$comm) ]
		  
		   ];	
	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);

	
	}
	
	
}