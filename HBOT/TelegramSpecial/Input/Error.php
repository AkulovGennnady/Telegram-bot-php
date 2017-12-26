<?php
namespace HBOT\TelegramSpecial\Input;

 
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//клавиатура кастромная
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;


class Error
{
	private $error;
	private $chat_id;
	
		
	function __construct($error, $chat_id) 
	{	
		$this -> error = $error;
		$this -> chat_id = $chat_id;
		
		$this -> perform();			
	}
	private function perform()
	{
				
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/Input/Error/'. $lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $this -> error;
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[		
		[ new InlineKeyboardButton($s0, '', 'cancel') ]
 		   ];		
		$TelegramMain = new TelegramMain();		
		$TelegramMain -> performApiRequest($sendMessage);	
	}
 
	
	
	
}