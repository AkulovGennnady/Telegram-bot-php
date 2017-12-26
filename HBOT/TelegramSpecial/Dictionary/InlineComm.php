<?php
$inlineComm  =  [
//invest commands (key => kommand)
		"chLang" => "ChangeMyLanguage", //+show menu on changed language
//change wallet	([0], [1] - wallet name)
		"chWallet" => "ChangeMyWallet", 		
//set upline
		"setUpline" => "SetMyUpline",	
//withdraw after choosing paym sys		
		"with" => "WithState",
//creation of deposit	
		"manually" => "InputAmountDepo", //set state ...
		
		"amount" => "ChoosePlan", //([0] - amount, [1] - 10)
		
		"plan" => "ChoosePaymSys",   //([0] - plan,[1] - plan_id(1.),  [2]- amount,[3] - 10)
		
		"paym_sys" => "GenerPayment", //([0],[1],  [2] - plan,[3]- plan_id(1.), [4]- amount,[5]- 10)
		
		"banner" => "BannerSend",	
		
		"cancel" => "Cancel"		
		];