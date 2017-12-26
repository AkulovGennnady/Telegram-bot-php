<?php
namespace HBOT\TelegramSpecial\Input;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramSpecial\InlineComm\ChoosePlan;

use HBOT\TelegramSpecial\Input\Error;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
 
class DepoAmount 
{
	private $curComm;
	private $chat_id;
	
	private $lang;
	
	private $dmin;
	private $dmax;
	
	function __construct($curComm, $chat_id) 
	{
 		$this -> curComm = $curComm;		
		$this -> chat_id = $chat_id;
		//curComm - (text) command
		$this -> setLang();
		$this -> setMinMax();
		
		$this -> execute();
	}
 
	private function execute()
	{	
		if ($this -> check())
		{
			$States = new States($this -> chat_id);
			$States -> setState('menu');
			
			$ChoosePlan = new ChoosePlan(['amount' , $this -> curComm], $this -> chat_id);			
		}	
	}
	
 	private function setMinMax()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`dmin`, `dmax`
				FROM
				`adminset`";
		$row = $db -> selectRow($sql);
		
		$this -> dmin = $row['dmin'];
		$this -> dmax = $row['dmax']; 	
	} 	
	
	private function check()
	{	
		if (!is_numeric($this -> curComm))
		{
			//if is not numeric
		   //if (!is_numeric($this -> curComm))
			$mustBeDigit = $this -> mustBeDigit();
			return 0;
		}
		elseif ($this -> curComm  <  $this -> dmin)
		{
			$tooLitle = $this -> tooLitle(); 
			return 0;
		}		
		elseif ($this -> curComm  >  $this -> dmax)
		{
			$tooMuch = $this -> tooMuch();
			return 0;
		}else
		{	
			//Right amount
			return 1;
		}		
	}
	
	private function setLang()
	{
		$States = new States($this -> chat_id);
		$lang = $States -> getLang();		
		$this -> lang = $lang; 
	}
	private function mustBeDigit()
	{
		require __DIR__ .'/../Locale/Input/DepoAmount/'. $this -> lang .'.php';		
		$Error = new Error($er1, $this -> chat_id);		
	}
	private function tooLitle()
	{ 
		require __DIR__ .'/../Locale/Input/DepoAmount/'. $this -> lang .'.php';		
		$Error = new Error($er2 ."<b>{$this -> dmin} USD</b>", $this -> chat_id);
	}
	private function tooMuch()
	{
		require __DIR__ .'/../Locale/Input/DepoAmount/'. $this -> lang .'.php';		
		$Error = new Error($er3 ."<b>{$this -> dmax} USD</b>", $this -> chat_id);
	}
		
}

 
