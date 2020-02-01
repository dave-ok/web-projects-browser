<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	define('CURR_DIR', getcwd());
	//echo CURR_DIR;
	define('JSON_FILE', CURR_DIR . '/assets/data.json');
	
	define('URI_PATH', str_replace('index.php', '', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
	define('URI_BASE',  "http://$_SERVER[HTTP_HOST]/");
	define('REQUEST_URI', $_SERVER['REQUEST_URI']);
	
	//set maxRows and maxColumns
	define('MAX_COLS', 2);
	define('MAX_ROWS', 2);
	define('VISIBLE_LINKS', 3);

	//max iteration depths
	define('MAX_SUBS_DEPTH', 2);
	define('MAX_MODIFIED_DEPTH', 3);

	//regular expressions
	//define('REGEX_PATH', '/^(?:https?:\/\/)?(?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})\/([\/\w]+)+\/([\d]+)?\/?$/');
	//define('REGEX_TAX', '^(?:https?:\/\/)?(?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})\/txm\/([\/\w]+)+\/([\d]+)?\/?$')
	define('REGEX_URL', '/^(?:https?:\/\/)?(?:[\da-z\.-]+)\.(?:[a-z\.]{2,6})\/?(?|(?<istax>txm)|())\/?(?|(?<urlpath>[a-zA-Z\_]+[\_0-9]*?)|())(?|(?:\/(?<pgnum>[\d]+))|())\/?$/');
	define('REGEX_BASE', '/^(?<urlbase>https?:\/\/[\da-z\.-]+\.[a-z\.]{2,6}\/?(?|(?<istax>txm)|())\/?(?|(?<urlpath>[a-zA-Z\_]+[\_0-9]*?)|()))(?|(?:\/(?<pgnum>[\d]+))|())\/?$/');
	
?>