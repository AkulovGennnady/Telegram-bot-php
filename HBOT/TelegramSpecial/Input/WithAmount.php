<?php
namespace HBOT\TelegramSpecial\Input;

use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;
 
use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage; 

use HBOT\Funds\WithFunds;

use HBOT\TelegramSpecial\Input\Error;

class WithAmount 
{
	private $paym_sys;
	private $curComm;
	private $chat_id;
	
	private $lang;
	
	private $wmin;
	private $wmax;

	
	function __construct($paym_sys, $curComm, $chat_id) 
	{
		$this -> paym_sys = $paym_sys;
 		$this -> curComm = $curComm;		
		$this -> chat_id = $chat_id;
		
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
			//WITHDRAW FUNDS
			$WithFunds = new WithFunds($this -> paym_sys, $this -> curComm, $this -> chat_id);	
		}	
	}
	
 	private function setMinMax()
	{
		$db = Database::getDB();
		$sql = "SELECT
				`wmin`,`wmax`
				FROM
				`adminset`";
		$row = $db -> selectRow($sql);
		
		$this -> wmin = $row['wmin'];
		$this -> wmax = $row['wmax']; 	
	}	
	
	private function check()
	{
		if (!is_numeric($this -> curComm))
		{
			$mustBeDigit = $this -> mustBeDigit();
			return 0;
		}
		elseif($this -> curComm  >  $this -> getBalance())
		{
			$moreThanBalance = $this -> moreThanBalance();
			return 0;		
		}
		elseif ($this -> curComm  <  $this -> wmin)
		{
			$tooLitle = $this -> tooLitle(); 
			return 0;
		}		
		elseif ($this -> curComm  >  $this -> wmax)
		{
			$tooMuch = $this -> tooMuch();
			return 0;
		}else
		{	
			//Right amount
			return 1;
		}
		
	}
	
	private function getBalance()
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
	
	private function setLang()
	{
		$States = new States($this -> chat_id);
		$lang = $States -> getLang();		
		$this -> lang = $lang; 
	}
	private function mustBeDigit()
	{
		require __DIR__ .'/../Locale/Input/WithAmount/'. $this -> lang .'.php';
		$Error = new Error($er1, $this -> chat_id);
	}
	
 	private function NullBall()
	{
		require __DIR__ .'/../Locale/MenuComm/Withdraw/'. $lang .'.php';		
		$Error = new Error($er2, $this -> chat_id);
	}
	
	private function moreThanBalance()
	{
		require __DIR__ .'/../Locale/Input/WithAmount/'. $this -> lang .'.php';		
		$Error = new Error($er2, $this -> chat_id);
	}
	
	private function tooLitle()
	{ 
		require __DIR__ .'/../Locale/Input/WithAmount/'. $this -> lang .'.php';		
		$Error = new Error($er3."<b>{$this -> wmin} USD</b>", $this -> chat_id);
	}
	private function tooMuch()
	{
		require __DIR__ .'/../Locale/Input/WithAmount/'. $this -> lang .'.php';
		$Error = new Error($er4 ."<b>{$this -> wmax} USD</b>", $this -> chat_id);
	}
		
}

 

 
