<?php
// autoload classes based on a 1:1 mapping from namespace to directory structure.
spl_autoload_register(function ($className) {


      $ds = DIRECTORY_SEPARATOR;
      $dir = __DIR__;

    // replace namespace separator with directory separator 
      $className = str_replace('\\', $ds, $className);

    // get full name of file containing the required class       
	 $file = "{$dir}{$ds}{$className}.php";
	

    // get file if it is readable
       if (is_readable($file)) 
		  require_once $file;		
		
});