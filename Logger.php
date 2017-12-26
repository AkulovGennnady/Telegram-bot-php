<?php
//class to log all errors
class Logger{
	
	private $logdir;
	private $logfile;
 
	function __construct($logfile = 'log.txt', $logdir = 'log')
	{
		$this -> logfile = $logfile;	
		$this -> logdir = $logdir;		
	}
	public function log($error = null, $descr = null)
	{
		$dir = __DIR__ . '/' . $this -> logdir .'/'. $this -> logfile;
		$time = gmdate('d-m-Y H:i:s');
		file_put_contents ($dir, "| {$time} | {$error} | {$descr} | \r\n", FILE_APPEND);
	}	
}