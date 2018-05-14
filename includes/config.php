<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	define('CURR_DIR', getcwd());
	//echo CURR_DIR;
	define('JSON_FILE', CURR_DIR . '/assets/data.json');
	
	define('URI_PATH', str_replace('index.php', '', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
	
	//set maxRows and maxColumns
	define('MAX_COLS', 2);
	define('MAX_ROWS', 5);

?>