<?php
namespace HBOT\TelegramAPI;
//not good pracrice :(
class Updates 
{
 //chat_id of the bot
 public $Message_Chat_id;
 //username of a user who send message/query
 public $Message_Chat_username; 
 //text of a message 
 public $Message_text;
 //callback_query from inline keyboard
 public $CallbackQuery_data;
 
 
 function __construct()
 {
	$this -> getUpdates(); 
 }
 /*
 *Get updates json and puts it in array
 *
 *
 */
 public function getUpdates()
 {	
	if ($upd_arr = json_decode(file_get_contents('php://input'), TRUE))
	{
		$this -> setValues($upd_arr);
		http_response_code(200);
	}
 }
 
  /*
 * sets all NEEDED values
 * input array
 *
 */
 public function setValues(array $upd_arr)
 {
	
	if (isset($upd_arr['message']))
	{
	  $this -> Message_Chat_id = $upd_arr['message']['chat']['id'];
	  
	  if (isset($upd_arr['message']['chat']['username']))
	  {
	    $this -> Message_Chat_username = $upd_arr['message']['chat']['username'];
	  }
      else 
	  {
		$this -> Message_Chat_username = '';
	  }	
		$this -> Message_text = $upd_arr['message']['text'];
	}else if (isset($upd_arr['callback_query']))
	{
	   $this -> Message_Chat_id = $upd_arr['callback_query']['message']['chat']['id'];
	   //$this -> Message_Chat_username = $upd_arr['callback_query']['message']['chat']['username'];
	   //$this -> Message_text = $upd_arr['callback_query']['message']['text'];
	   $this -> CallbackQuery_data = $upd_arr['callback_query']['data'];
	   
	}else 
	{
		//wrong tipe of update (or not supported)
		$Logger = new \Logger('Updates.txt');
		$Logger -> log('Fake update', json_encode($upd_arr));
		 
	}
	
 }
  
}