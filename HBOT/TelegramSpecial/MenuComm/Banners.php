<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class Banners
{
	public $chat_id;

	function __construct($chat_id)
	{
		$this -> chat_id = $chat_id;
		$this -> perform();
	}
	public function perform()
	{	
		$this -> sendMessage();
	}
 
	private function sendMessage()
	{		
	    $States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/Banners/'.$lang.'.php';	
		
 		//row of sizes
		require __DIR__ . '/../../config/banners.php';
		//$size = ["125x125","468x60", "728x180", "720x300"];
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		 [ new InlineKeyboardButton($size[0], '', 'banner '.$size[0]), 
		   new InlineKeyboardButton($size[1], '', 'banner '.$size[1])]
		  
		,[ new InlineKeyboardButton($size[2], '', 'banner '.$size[2]),		  
		   new InlineKeyboardButton($size[3], '', 'banner '.$size[3])]
		 /* 
		,[ new InlineKeyboardButton($size[4], '', 'banner '.$size[4]),
		   new InlineKeyboardButton($size[5], '', 'banner '.$size[5])]
		*/
		];	
	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);		
	}
	
}
