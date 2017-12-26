<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

use HBOT\TelegramAPI\Types\ReplyKeyboardMarkup;
use HBOT\TelegramAPI\Types\KeyboardButton;

use HBOT\TelegramSpecial\Systemic\States;

class Partnership
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
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $menu[6];	
		$sendMessage->reply_markup = new ReplyKeyboardMarkup();
		$sendMessage->reply_markup -> resize_keyboard = true;
		
		$sendMessage-> reply_markup -> keyboard = [
		[ new KeyboardButton("$menu[12]"), 
		  new KeyboardButton("$menu[13]") ],
		  
		  [ new KeyboardButton("$menu[14]"), 
			new KeyboardButton("$menu[15]") ],
		  
		  [ new KeyboardButton("$menu[16]"), 
			new KeyboardButton("$menu[0]") ] 
		];

		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}