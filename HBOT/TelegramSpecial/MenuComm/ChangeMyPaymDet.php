<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
//custom keyboard
use HBOT\TelegramAPI\Types\InlineKeyboardMarkup;
use HBOT\TelegramAPI\Types\InlineKeyboardButton;

class ChangeMyPaymDet
{
	public $chat_id;

	function __construct($chat_id)
	{
		$this -> chat_id = $chat_id;
		$this -> perform();
	}
	public function perform()
	{
		//get wallets
		$db = Database::getDB();
		$sql = "SELECT
				`PM`,`AC`,`PY`,`BTC`
				FROM
				`wallets`
				WHERE 
				`chat_id` = ?";
		$row = $db -> selectRow($sql, [$this -> chat_id]);
					
		//get lang
		$States = new States($this -> chat_id);
		$lang = $States -> getLang(); 
		require __DIR__ .'/../Locale/MenuComm/ChangeMyPaymDet/'.$lang.'.php'; 
		
		//available wallets
		require __DIR__ .'/../../config/wallets.php';
		
		
		//send message
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;	
		$sendMessage->reply_markup = new InlineKeyboardMarkup();
		$sendMessage-> reply_markup -> inline_keyboard =[
		
		 [ new InlineKeyboardButton($psname[0].' : '.$row['BTC'], '', 'chWallet '.$ps[0])] 
		,[ new InlineKeyboardButton($psname[1].' : '.$row['AC'], '', 'chWallet '.$ps[1])] 
		,[ new InlineKeyboardButton($psname[2].' : '.$row['PM'], '', 'chWallet '.$ps[2])] 
		,[ new InlineKeyboardButton($psname[3].' : '.$row['PY'], '', 'chWallet '.$ps[3])] 
 	  
														];		
		$TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage);
	}
	
}
