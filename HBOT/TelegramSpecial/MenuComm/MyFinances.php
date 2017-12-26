<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

use HBOT\TelegramAPI\Types\ReplyKeyboardMarkup;
use HBOT\TelegramAPI\Types\KeyboardButton;

use HBOT\TelegramSpecial\Systemic\States;
use HBOT\DB\Database;

class MyFinances
{
	public $chat_id;	
	
	function __construct($chat_id)
	{
		$this -> chat_id = $chat_id;
		$this -> perform();
	}
	public function perform()
	{ 
		$States = new States($this -> chat_id);
		$lang = $States -> getLang();
		//load commands
		//require_once - NOT WORKING!!! it was required before? but not visible...
        // require_once __DIR__ .'/../Locale/'.$lang.'.php';
		
		require __DIR__ .'/../Locale/Menu/'.$lang.'.php'; 
		
		//Class locale
		require __DIR__ .'/../Locale/MenuComm/MyFinances/'.$lang.'.php'; 
		
		//GET USER BALACE FORM ubalance
		$balance = 0.0;
		
		$sql = "SELECT `bal` FROM `ubalance` WHERE `chat_id` = ?";
		$db = Database::getDB();
		$balance = $db -> selectCell($sql, [$this -> chat_id]);
		
		
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $yourbal.' <b>'.number_format($balance, 2, '.', ' ').' USD</b>';	
		$sendMessage->reply_markup = new ReplyKeyboardMarkup();
		$sendMessage->reply_markup -> resize_keyboard = true;
		
		$sendMessage-> reply_markup -> keyboard = [
		[ new KeyboardButton("$menu[9]"), 
		  new KeyboardButton("$menu[10]") ],
		  
		  [ new KeyboardButton("$menu[11]"), 
			new KeyboardButton("$menu[0]") ]
 
		];

		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}