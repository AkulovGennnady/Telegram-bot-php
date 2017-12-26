<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\DB\Database;

use HBOT\TelegramSpecial\Systemic\States;
use HBOT\TelegramSpecial\Systemic\PotenPay;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class GenerPayment 
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
		require __DIR__ .'/../Locale/InlineComm/GenerPayment/'. $lang .'.php';
		
		//paym_sys PM plan 1 amount 10 (0-5)
		//$comm = $this -> curComm[0].' '.$this -> curComm[1].' '.$this -> curComm[2].' '.$this -> curComm[3].' '.$this -> curComm[4].' '.$this -> curComm[5];
		
		
		/*PUT INFO IN Database as poten_pay 
		  generate link of payment ($this -> curComm[1] is payment system!)
		*/
		//PUT INFO IN Database as poten_pay

		$PotenPay = new PotenPay($this -> curComm, $this -> chat_id);
		//get generated link of payment
		$url = $PotenPay -> getURL();
        //file_put_contents ('GenerPayment.txt', "| url | {$url} | \r\n", FILE_APPEND);
		//& is not seen in telegram!
		$url = rawurlencode ($url);		
		
		//FINALY, SEND IT TO TELEGRAM
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $s0; 		
	     
		 $sendMessage->reply_markup = new InlineKeyboardMarkup();
		 $sendMessage-> reply_markup -> inline_keyboard =[		
		 [ new InlineKeyboardButton($s1, $url, '') ] 
		 ];		 
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);	
	}
	
	
}