<?php
namespace HBOT\TelegramSpecial\Systemic;

use HBOT\DB\Database;

class States
{
	private $chat_id;
	//private $state;
	//private $lang;
	
	function __construct($chat_id)
	{
		$this -> chat_id = $chat_id;
	}
	
	public function getState()
	{
		return $this -> getOne('state');
	}
	
	public function getLang()
	{		
		return $this -> getOne('lang');
	}
	
	//
	public function setState($state)
	{
		return $this -> setOne($state, 'state');
	}
	
	public function setLang($lang)
	{		
		return $this -> setOne($lang, 'lang');
	}
	
	
	private function getOne($cell)
	{
		$db = Database::getDB();
		$sql = "SELECT `{$cell}`
				FROM `users`
				WHERE
				`chat_id` = ?";
				
		$param = $db -> selectCell($sql, [$this -> chat_id]);
		
		return $param;		
	}
	
	private function setOne($param, $column)
	{
		$db = Database::getDB();
		$sql = "UPDATE `users`
				SET
				`{$column}` = '{$param}'
				WHERE
				`chat_id` = ?";
				
		$id = $db -> query($sql, [$this -> chat_id]);
		
		return $id;		
	}
}