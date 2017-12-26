<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class Invest
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
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/MenuComm/Invest/'. $lang .'.php';
		
		//row of buttons to deposit
		//$amo = [10, 20, 25,  50, 75, 100];
		//array 3 x 3  $amo
		require __DIR__ .'/../../config/depokeyb.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		[ new InlineKeyboardButton($amo[0].' USD', '', 'amount '.$amo[0]), 
		  new InlineKeyboardButton($amo[1].' USD', '', 'amount '.$amo[1]),
		  new InlineKeyboardButton($amo[2].' USD', '', 'amount '.$amo[2])],
		  
		[ new InlineKeyboardButton($amo[3].' USD', '', 'amount '.$amo[3]), 
		  new InlineKeyboardButton($amo[4].' USD', '', 'amount '.$amo[4]),
		  new InlineKeyboardButton($amo[5].' USD', '', 'amount '.$amo[5])],
		 
		[ new InlineKeyboardButton($amo[6].' USD', '', 'amount '.$amo[6]), 
		  new InlineKeyboardButton($amo[7].' USD', '', 'amount '.$amo[7]),
		  new InlineKeyboardButton($amo[8].' USD', '', 'amount '.$amo[8])],

      /*[ new InlineKeyboardButton($amo[9].' USD', '', 'amount '.$amo[9]), 
		  new InlineKeyboardButton($amo[10].' USD', '', 'amount '.$amo[10]),
		  new InlineKeyboardButton($amo[11].' USD', '', 'amount '.$amo[11])],
	  */	  
		  
		[ new InlineKeyboardButton($s1, '', 'manually')]		  
		   ];	
	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}
