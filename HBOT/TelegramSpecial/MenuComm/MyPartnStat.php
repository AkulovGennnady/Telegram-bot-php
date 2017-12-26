<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class MyPartnStat
{
	 
	private $chat_id;
	
		
	function __construct($chat_id) 
	{	
 		$this -> chat_id = $chat_id;		
		$this -> perform();			
	}
	private function perform()
	{
		$this ->sendMessage();
	}
	private function getPartnStat()
	{
		$db = Database::getDB();
		$sql = "SELECT 
				count(`id`)
				FROM
				`users` 
				WHERE
				`upline_id` = ?";
		$stat['t_invit'] = $db -> selectCell($sql, [$this->chat_id]);
		if (!$stat['t_invit']) $stat['t_invit'] = 0;
			
		$sql = "SELECT 
				sum(`amount`)
				FROM
				`depos` LEFT JOIN `users` ON `depos`.`chat_id` = `users`.`chat_id`
				WHERE
				`users`.`upline_id` = ?";
		$stat['t_invest'] = $db -> selectCell($sql, [$this->chat_id]);
		if (!$stat['t_invest']) $stat['t_invest'] = 0;
		
		$sql = "SELECT 
				sum(`amount`)
				FROM
				`ref_paid`
				WHERE
				`chat_id` = ?";
		$stat['t_ref'] = $db -> selectCell($sql, [$this->chat_id]);
		if (!$stat['t_ref']) $stat['t_ref'] = 0;
		
		$now = time();
		$today = $now - strtotime('today');//seconds from 0:00:00 today
		$sql = "SELECT 
				sum(`amount`)
				FROM
				`ref_paid`
				WHERE
				`chat_id` = ?
				AND
				{$now} - `time` <= {$today}";
		$stat['tt_ref'] = $db -> selectCell($sql, [$this->chat_id]);
		if (!$stat['tt_ref']) $stat['tt_ref'] = 0;
		return $stat;
	}
 
	private function sendMessage()
	{		
	    $States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/MyPartnStat/'.$lang.'.php';	 

		$stat = $this -> getPartnStat();
		$statstr = "{$s1}{$stat['t_invit']}%0A{$s2}{$stat['t_invest']} USD%0A{$s3}{$stat['t_ref']} USD%0A{$s4}{$stat['tt_ref']} USD";
		
	   	$sendMessage = new sendMessage;
		$sendMessage->chat_id = $this -> chat_id;
		$sendMessage->parse_mode = 'HTML';			
		$sendMessage->text = "{$s0}%0A{$statstr}";
        $TelegramMain = new TelegramMain();
		$TelegramMain -> performApiRequest($sendMessage); 
		
	}
	
	
	
}