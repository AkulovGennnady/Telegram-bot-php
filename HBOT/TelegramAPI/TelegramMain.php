<?php
namespace HBOT\TelegramAPI;

use HBOT\TelegramAPI\Abstracts\TelegramMethods;
/**
 * The main API which does it all
 */
class TelegramMain
{
	//the last used method
	public $methodName;
     /**
     * Stores the API URL from Telegram
     * @var string
     */
    private $apiUrl;   
	//url with parameters to send
	private $paramUrl;
	//raw data, responce from Telegram	private $response;
	
		
    //criates the class and apiUrl
    public function __construct()
    {
       $this->constructApiUrl();
    }

	 /**
     * Builds up the Telegram API url
     * 
     */
    final private function constructApiUrl()
    {
		require_once 'bset.php';
        $this->apiUrl = 'https://api.telegram.org/bot' . BOT_TOKEN;
    }
    /**
     * Prepares and sends an API request to Telegram
     *
     * @param TelegramMethods $method
     * @return TelegramTypes
     */
    public function performApiRequest(TelegramMethods $method)
    {
		
		 
		$this->checkSpecialConditions($method); //json encodes reply_markup 
        $this->sendRequestToTelegram($method);

    }
	
	public function buildUrlParamStr(TelegramMethods $method)
	{		
		$this -> paramUrl = urldecode (http_build_query ($method->export()) ); // exports to array all fields - bilds query
				
		return $this -> paramUrl;
	}
    /**
     * This is the method that actually sends request
     *
	 * @param TelegramMethods $method
     */
    private function sendRequestToTelegram(TelegramMethods $method) /*TelegramRawData*/
    {
        //curl
		//and then get response
		$response = $this -> post($this->composeApiMethodUrl($method));
		/*
			Here you can make smth with response
		*/
		
		//print_r($response);
        //file_get_contents('https://api.telegram.org/bot328910310:AAF1hhUoLCe1U_KBqHbvHSGX0RqgQuf56rs/sendMessage?chat_id=262492945&text='.$response);		
		
		//echo  $json_string = json_encode($response, JSON_PRETTY_PRINT);
    }  

    /**
     * Can perform any special checks needed to be performed before sending the actual request to Telegram
     * Now checks if reply markup is used and json-encodes it
     *
     * @param TelegramMethods $method
     */
    private function checkSpecialConditions(TelegramMethods $method)
    {     
        $method->check_reply_markup();
    }

    /**
     * Builds up the URL with which we can work with
     *
     * All methods in the Bot API are case-insensitive.
     * All queries must be made using UTF-8.
     *
     * @param TelegramMethods $method
     * @return string
     */
    private function composeApiMethodUrl(TelegramMethods $method): string
    {    
		//with namespaces! like: TelegramAPI\Methods\SendMessage
        $completeClassName = get_class($method);
		//without namespaces! like: SendMessage
        $this->methodName = substr($completeClassName, strrpos($completeClassName, '\\') + 1);		
		$this->paramUrl = $this->buildUrlParamStr($method); 
		
		//echo $this->apiUrl .'/'. $this->methodName . '?' . $this->paramUrl;
		
        return $this->apiUrl .'/'. $this->methodName . '?';
    }

	/*
	* make request to Telegram, sends url
	* input - complex url string  
	* returns responce from Telegram
	*/
	private function post (string $url)
	{
		try {				 
				
			$handle = curl_init();			
			curl_setopt_array($handle, array(
				CURLOPT_URL => $url,
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $this->paramUrl
				));		
			//curl_setopt($ch, CURLOPT_ENCODING ,"");
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8'));
			
			$response = curl_exec($handle);		
			$resp = json_decode($response);			
			if ($resp['ok'] == false)
				throw new \Exception($resp['description']);			
			
		} catch ( \Exception $e) {
			$Logger = new \Logger('TelegramMain.txt');
			$Logger -> log($response, $url.$this->paramUrl);
		}finally{			
			curl_close($handle);		
			return $response;			
		}

	}
	

}
