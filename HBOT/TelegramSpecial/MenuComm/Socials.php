<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class Socials
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
		//load message		
		require __DIR__ .'/../Locale/MenuComm/Socials/'.$lang.'.php'; 
		//load socials
		require __DIR__ .'/../../config/socials.php';
		
 
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
 		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		
		 [ new InlineKeyboardButton($social[0], $url[0], ''), 
		   new InlineKeyboardButton($social[1], $url[1], '')]
		  
		,[ new InlineKeyboardButton($social[2], $url[2], ''), 
		   new InlineKeyboardButton($social[3], $url[3], '')]
		   
		   
 		];

		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}