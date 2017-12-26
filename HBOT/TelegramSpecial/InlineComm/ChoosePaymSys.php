<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\DB\Database;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class ChoosePaymSys 
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
		require __DIR__ .'/../Locale/InlineComm/ChoosePaymSys/'. $lang .'.php';
		
		//plan 1 amount 10
		$comm = $this -> curComm[0].' '.$this -> curComm[1].' '.$this -> curComm[2].' '.$this -> curComm[3];
		
		//available payment systems
		// $psname = ['PerfectMoney', 'AdvancedCash', 'Payeer', 'Bitcoin'];
			// $ps = ['PM', 'AC', 'PY', 'BTC'];
		require __DIR__ .'/../../config/paymsysdepo.php';
		
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		
		[ new InlineKeyboardButton($psname[0], '', 'paym_sys '.$ps[0].' '.$comm) ]
	   ,[ new InlineKeyboardButton($psname[1], '', 'paym_sys '.$ps[1].' '.$comm) ]
	   ,[ new InlineKeyboardButton($psname[2], '', 'paym_sys '.$ps[2].' '.$comm) ]  
	   ,[ new InlineKeyboardButton($psname[3], '', 'paym_sys '.$ps[3].' '.$comm) ]
		   ];	
	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);

	
	}
	
	
}