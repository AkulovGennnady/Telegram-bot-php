<?php
namespace HBOT\TelegramSpecial\MenuComm;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;


class MyActiveInvestm
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
	private function getActiveDepo()
	{
		$db = Database::getDB();
		$sql = "SELECT `depos`.`id`, `name`, `pers`, `amount`, `paym_sys`, `time`, `lpt`, `earned`, `p_num`, `numofpaym`, `period`
				FROM
				`depos` LEFT JOIN `plans` ON `depos`.`plan_id` = `plans`.`id` 
				WHERE 
				`depos`.`chat_id` = ? 
				AND 
				`depos`.`is_active` = '1'";
		$deposar = $db -> select($sql, [$this->chat_id]);
		return $deposar;
	}
 
	private function sendMessage()
	{		
	    $States = new States($this -> chat_id);
		//get Language
		$lang = $States -> getLang($this -> chat_id);		
		//and show About us depending on Locale
		require __DIR__ . '/../Locale/MenuComm/MyActiveInvestm/'.$lang.'.php';	 

		$deposar = $this -> getActiveDepo();
		
		$day = 86400;
		$depostr = '';
		
		$sendMessage = new sendMessage;		
		$TelegramMain = new TelegramMain();
		$sendMessage->parse_mode = 'HTML';	
		$sendMessage->chat_id = $this -> chat_id;
		
		if ($deposar){
			//Your active investments:				
			$sendMessage->text = "{$header}%0A";
			$TelegramMain -> performApiRequest($sendMessage);
			//each active
				foreach ($deposar as $depo)
				{			
					$depostr = "%0A{$s0}{$depo['id']}%0A{$s1}{$depo['name']}%0A{$s2}{$depo['amount']} USD%0A{$s3}{$depo['paym_sys']}%0A{$s4}".gmdate("d-M-Y H:i", $depo['time'])."%0A{$s5}{$depo['earned']} / ".$depo['amount']*$depo['pers']*$depo['numofpaym']." USD%0A{$s6}{$depo['p_num']} / {$depo['numofpaym']}%0A{$s7}".gmdate("d-M-Y H:i", ($depo['lpt'] + $depo['period'] * $day) )."%0A";
					//new one depo
					$sendMessage->text = "%0A{$depostr}";				
					$TelegramMain -> performApiRequest($sendMessage);
				}
		}else {
			//no active
			$sendMessage->text = "{$er0}";		
			$TelegramMain -> performApiRequest($sendMessage);			
		}
				
	}
	
	
	
}