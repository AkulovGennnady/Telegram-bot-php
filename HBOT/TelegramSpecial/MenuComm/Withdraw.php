<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

//клавиатура кастромная
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class Withdraw
{
	private $chat_id;	
		
	function __construct($chat_id) 
	{	
		$this -> chat_id = $chat_id;
		
		$this -> perform();			
	}
	private function perform()
	{
		if ( $this->checkBal() > 0)
		{
			//$this -> setState();
			$this -> showPaymSys();	
		}
		else
		{
			$this -> NullBall();
		}
	}
 
	private function showPaymSys()
	{
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/MenuComm/Withdraw/'. $lang .'.php';
		
		//available payment systems
		// $psname = ['PerfectMoney', 'AdvancedCash', 'Payeer', 'Bitcoin'];
			// $ps = ['PM', 'AC', 'PY', 'BTC'];
		require __DIR__ .'/../../config/paymsyswith.php';
		
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		
		[ new InlineKeyboardButton($psname[0], '', 'with '.$ps[0].' '.$comm) ]
	   ,[ new InlineKeyboardButton($psname[1], '', 'with '.$ps[1].' '.$comm) ]
	   ,[ new InlineKeyboardButton($psname[2], '', 'with '.$ps[2].' '.$comm) ] 
	   ,[ new InlineKeyboardButton($psname[3], '', 'with '.$ps[3].' '.$comm) ]  	   
		   ];	
	
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	private function checkBal()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`bal`
				FROM
				`ubalance`
				WHERE
				`chat_id` = ?";
		$bal = $db -> selectCell($sql, [$this -> chat_id]);
		return $bal;
	}
	private function NullBall()
	{
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/MenuComm/Withdraw/'. $lang .'.php';
		
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->parse_mode = 'HTML';
		$sendMessage->text = $s1;
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}