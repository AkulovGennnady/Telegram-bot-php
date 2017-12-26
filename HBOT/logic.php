<?php
require_once '../autoload.php';

use HBOT\TelegramAPI\Updates;
//add new user
use HBOT\TelegramSpecial\Systemic\NewUser;
//text(menu) commands executor
use HBOT\TelegramSpecial\MenuCommExec;
//inline commands executor
use HBOT\TelegramSpecial\InlineCommExec;
//to get/set user state
use HBOT\TelegramSpecial\Systemic\States;
//MANUAL INPUT (!)
//invest
use HBOT\TelegramSpecial\Input\DepoAmount;
//upline
use HBOT\TelegramSpecial\Input\Upline;
//UpdateWallet
use HBOT\TelegramSpecial\Input\UpdateWallet; 
//WithAmount
use HBOT\TelegramSpecial\Input\WithAmount;

use HBOT\TelegramSpecial\InlineComm\InputAmountDepo;
 
//get updates
$Updates = new Updates();
if ($Updates -> Message_text)
{
	// PERFORM MESSAGE HERE	
	//NEW USER?
	//message may include ref id after start
			$command = explode(" ", $Updates -> Message_text);
			if ($command[0] == "/start")
			{
				//if message includes ref id
				if (!isset($command[1]))
					$command[1] = '0';			
				$NewUser = new NewUser($Updates -> Message_Chat_id, $Updates -> Message_Chat_username, $command[1]);
				die();
			}	
	//NOT NEW USERS	
	$States = new States($Updates -> Message_Chat_id);
	$state = $States -> getState();
	//states of users
	switch ($state){
		case 'menu':
			$MenuCommExec = new MenuCommExec($Updates -> Message_text, $Updates -> Message_Chat_id);
			break;
		case 'manually':
			$DepoAmount = new DepoAmount ($Updates -> Message_text, $Updates -> Message_Chat_id);
			break;
		case 'upl':
			$Upline = new Upline ($Updates -> Message_text, $Updates -> Message_Chat_id);
			break;
		case 'PM':
		case 'AC':
		case 'PY':
		case 'BTC':
			$UpdateWallet = new UpdateWallet ($state,$Updates -> Message_text, $Updates -> Message_Chat_id);
			break;
		case 'with AC':
		case 'with PM':
		case 'with PY':
		case 'with BTC':
			$paym_sys = explode(" ", $state);
			$WithAmount = new WithAmount ($paym_sys[1], $Updates -> Message_text, $Updates -> Message_Chat_id);
			break;			
		}
}else if ($Updates -> CallbackQuery_data)
{
	// PERFORM INLINE DATA HERE
	$InlineCommExec = new InlineCommExec($Updates -> CallbackQuery_data, $Updates -> Message_Chat_id);
} 