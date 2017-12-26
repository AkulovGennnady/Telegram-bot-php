<?php 
use HBOT\DB\Database;
use HBOT\TelegramSpecial\Systemic\States;
use HBOT\TelegramAPI\TelegramMain;
use HBOT\TelegramAPI\Methods\SendMessage;
require_once __DIR__ .'/../../autoload.php';

$start = microtime(true);  
$db = Database::getDB();   
$now = time(); //current UNIX time in seconds
$day = 86400;//1 day in seconds
//select all `id` of depos if deposit is active and time of paying is gone
$sql = "SELECT 
        `d`.`id`, `d`.`chat_id`, `d`.`amount`, `p`.`pers`
		FROM 
		`depos` `d` LEFT JOIN `plans` `p` ON `d`.`plan_id` = `p`.`id`
		WHERE 
		`d`.`is_active` 
		AND 
		(('{$now}'-`d`.`lpt`) >= (`p`.`period` * '{$day}'))"; 

$total = $db -> select($sql);
 
 /* echo "<br>";
 echo "SELECT id ";
 echo "<pre>"; 
 echo print_r($total); 
 echo "<br>"; */
// echo "SELECT error ".$mysqli->error . "<br>";
// echo "SELECT affected_rows ".$mysqli->affected_rows . "<br>";

 //UPDATE depo and ubalance on id key
 if (isset($total))
 foreach ($total as $arr)
 {  
  $upd = "UPDATE 
		  `depos` `d`  LEFT JOIN `plans` `p`  ON `d`.`plan_id` = `p`.`id`
           LEFT JOIN `ubalance` `u` ON `d`.`chat_id` = `u`.`chat_id` 
		   SET 
		   `d`.`lpt` = `d`.`lpt` + (`p`.`period` * '{$day}'),
		   `d`.earned = `d`.`earned` + `d`.`amount` * `p`.`pers`, 
		   `d`.`p_num` = `d`.`p_num` + 1, 
		   `u`.`bal` = `u`.`bal` + `d`.`amount` * `p`.`pers` 
		   WHERE 		  		    		   
		   `d`.`id` = '{$arr['id']}'
		   ";
	$result = $db -> query($upd); 
 }	
 
/*  echo "<br>";
 echo "UPDATE affected_rows ".$result . "<br>"; */
 
 //FINISH DEPO on criteria number of payments 
if (isset($total))
 foreach ($total as $arr)
 {  
$finish = "UPDATE 
		  `depos` `d`  LEFT JOIN `plans` `p`  ON `d`.`plan_id` = `p`.`id`
            SET 
		   `d`.`is_active` = 0		   
		   WHERE
		   `d`.`p_num` >= `p`.`numofpaym`
		   AND
		   `d`.`id` = '{$arr['id']}' 
		   ";
	$result = $db -> query($finish); 
 }	
 
/*  echo "<br>";
 echo "finish affected_rows ".$result . "<br>"; 
 echo "<br>"; */
 //send Notification
 if (isset($total))
	 sendNotification($total);
 
 
  function sendNotification($total)
  {
	$sendMessage = new SendMessage(); 
    $TelegramMain = new TelegramMain(); 
	
	
	$db = Database::getDB();
	
	foreach ($total as $arr) 
	{
		
		$chat_id = $arr['chat_id'];
		$payment = $arr['amount'] * $arr['pers'];
		
		//locale file
		$States = new States($chat_id);
		$lang = $States -> getLang();
		require_once __DIR__ .'/../TelegramSpecial/Locale/Count/AddPers/'.$lang.'.php';
		//sending message	
		$sendMessage->chat_id = $chat_id;
		//$sendMessage->parse_mode = 'HTML';		
		$sendMessage->text = "{$s0} <b>{$payment}</b> {$s1} <b>{$arr['id']}</b>"; 
		$TelegramMain -> performApiRequest($sendMessage); 		
	}
  }
  
 //echo  'Time of execution: '.number_format((microtime(true) - $start), 4).' sec';

?>