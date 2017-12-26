<?php
namespace HBOT\TelegramSpecial\InlineComm;

use HBOT\TelegramSpecial\Systemic\States;

use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;

use HBOT\TelegramAPI\Methods\SendDocument;


class BannerSend 
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
		$this -> sendMessage();
	}
	private function sendMessage()
	{
		$States = new States($this -> chat_id);
        $lang = $States -> getLang();		
		//locale file of a class s0, s1....
		require __DIR__ .'/../Locale/InlineComm/BannerSend/'. $lang .'.php';		
		//banners config
		require __DIR__ . '/../../config/banners.php'; 		
        $TelegramMain = new TelegramMain();
		
		/*
		//Preview will be send with the code of the banner
		//banner of selected size
		$sendDocument = new sendDocument;
		$sendDocument->document = ${'id_'.$this->curComm[1]}; //"CgADBAADIgwAArseZAcfH_vyVBLraAI";
		$sendDocument->chat_id = $this -> chat_id; 
		$TelegramMain -> performApiRequest($sendDocument);
		*/
		
		//Copy this code...
		$sendMessage = new sendMessage();
		$sendMessage->chat_id = $this -> chat_id;	
		$sendMessage->text = $s0;		
		$TelegramMain -> performApiRequest($sendMessage);	
		
		//code of banner 
		$code = ($p1. $this ->chat_id .$p2. $this->curComm[1] .$p3);		
	    $sendMessage->text = $code;		
		$TelegramMain -> performApiRequest($sendMessage);
		
		//code of banner in <code> NOT WORKING
/* 		$sendMessage->parse_mode = 'HTML';		
		$code = htmlentities($p1. $this ->chat_id .$p2. $this->curComm[1] .$p3, ENT_NOQUOTES);		
	    $sendMessage->text = '<code>'. $code .'</code>';		
		$TelegramMain -> performApiRequest($sendMessage); */

	}		
}

