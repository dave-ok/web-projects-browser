<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	define('CURR_DIR', getcwd());
	//echo CURR_DIR;
	define('JSON_FILE', CURR_DIR . '/assets/data.json');
	define('URI_PATH', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

	//header('Vary: Origin');

	//create directories that we need
	$dirsNeeded = ['./assets'];
	$lenDirs = count($dirsNeeded);

	for ($i=0; $i < $lenDirs; $i++) { 
		if (!file_exists($dirsNeeded[$i]) && !is_dir($dirsNeeded[$i])) {
	   	mkdir($dirsNeeded[$i]);         
		} 	
	}

	
	
	function convertPathToWin($filePath)
	{
		//strip off /media/devroot/
		$strpath = str_replace('/media/devroot/', '', $filePath);
		//convert all forward slashes to back slashes
		$strpath = 'C:\\Dev\\' . str_replace( '/', '\\', $strpath);
		//append to windows root 'C:\\Dev\\
		return strtolower($strpath);
	}	

	function getLatestModifiedDate($dir, $maxDepth = 3, $latestModDate = [ 0, ''], $currDepth = 0) {    
	   
	   $currDepth += 1; //update depth each time we go deeper

		$cdir = scandir($dir);

		//traverse directory tree to max depth of maxDepth
	   foreach ($cdir as $key => $value) 
	   { 	   	

	      if (!(in_array(substr($value, 0, 1), ['.', '_'])) )	      
	      { 
	      	$filename = $dir . DIRECTORY_SEPARATOR . $value;
	      	
	      	//get modify time of curr file
		   	$mtime = filemtime($filename);

		   	//check against previous latest modify date and pick latest of the two
		      $latestModDate = $mtime > $latestModDate[0] ? [$mtime, $filename] : $latestModDate;

	         if (is_dir($filename)) 
	         { 	 
	         	echo " <br> entering dir ($currDepth): $filename <br>";        	
	         	if (($currDepth + 1) > $maxDepth) 
	         	{	         		
	         		//if depth is greater than maxdepth then goto next file/dir	         		
	         		continue;
	         	}
	         	else 
	         	{
		         	$latestModDate = getLatestModifiedDate($filename, $maxDepth, $latestModDate, $currDepth);	
	         	}	         	
	         	 
	         } 
	         
	      } 
	   } 
	   
	   return $latestModDate; 
	} 
	function changeDirModifiedDate($dirname)
	{
		//change to directory containing
		chdir('../assets/scripts');
		
		//run linux script to update modified times recursively
		//echo(getcwd());
		//touch -t YYMMDDhhmm /path/to/directory
		shell_exec("touch -t ");

		//return to original directory
		chdir(CURR_DIR);

		//return the latest modified date
		return $modDate;

	}

	function getDirList($currDir = CURR_DIR, $maxDepth = 2, $setModifiedTimes = false, $currDepth = 0, $parent = '')
	{				

		//update current depth
		$currDepth += 1;

		$winDir = convertPathToWin($currDir . '/');
		$scanned_dir = array_diff(scandir($currDir), array('..', '.'));

		//echo convertPathToWin($fname);
		$dirList = [];

		 foreach ($scanned_dir as $key => $fname) {
		 	

		 	$dirPath = $currDir . '/' . $fname;
		 	$baseName =  $dirPath . '/index.';

		 	if (is_dir($dirPath)) {			 		

		 		//in each directory look for index.html or index.php
		 		if (file_exists($baseName . 'php') || file_exists($baseName . 'html')) 
		 		//if found stop looking
		 		{		 			
		 			//if found add directory and win path to dirList
		 			//convert linux path to windows path
		 			//$fname = strtolower($fname);

		 			$dirList[$fname]['path'] =  $winDir . $fname;

		 			//echo $dirList[$fname]['path'] . '<br>';

		 			$dirList[$fname]['url'] = URI_PATH . $fname;
		 			$dirList[$fname]['depth'] = $currDepth;
		 			$dirList[$fname]['parent'] = $parent;

		 			if ($currDepth > 1) 
		 			{
		 				$dirList[$fname]['has_parent'] = true;		 				
		 			}
		 			else 
		 			{
		 				$dirList[$fname]['has_parent'] = false;
		 			}

		 		}
		 		//if not found and subdir search is permitted
		 		else 
		 		{
		 			//if no index file found and going deeper is allowed		 			
		 			if ($currDepth < $maxDepth) {
		 						 				
		 				$returnedList = getDirList($dirPath, $maxDepth, $setModifiedTimes, $currDepth, $fname);

		 				//if the returned array is not empty add parent to dirList
		 				if (!(empty($returnedList))) {		 					

		 					if ($currDepth > 1) 
				 			{
				 				$dirList[$fname]['has_parent'] = true;		 				
				 			}
				 			else 
				 			{
				 				$dirList[$fname]['has_parent'] = false;
				 			}

				 			$dirList[$fname]['path'] =  $winDir . $fname;		 					
		 					$dirList[$fname]['depth'] = $currDepth;
		 					$dirList[$fname]['parent'] = $parent;
		 					$dirList[$fname]['is_parent'] = true;
		 					$dirList[$fname]['url'] = '#';

		 					//append the children to dirList
				 			$dirList = array_merge($dirList, $returnedList);
				 		}



		 			}
		 		}
		 	}
		 }

		/* if ($setModifiedTimes) {
		 	//for each directory set modified date to latest
		 	foreach ($dirList as $dir => $value) {
		 		$mdate = changeDirModifiedDate($dir);
		 		$dirList[$dir]['modified_date'] = $mdate;
		 	}
		 } */
		 
		 return $dirList;
	}

	function getJsonDir($fname) //fxn to parse json file and return array
	{
		$dirArr = []; //initialize array
		
		$jsonDir = file_get_contents($fname);
		if ($jsonDir) {
			$dirArr = json_decode(strtolower($jsonDir), true);			
		}

		return $dirArr;
	}

	function buildJsonFile($dirArray, $fname) //build json file from array of directories
	{
		$jsonDir = json_encode($dirArray, JSON_FORCE_OBJECT);

		if ($jsonDir) {
			file_put_contents($fname, $jsonDir);
			return true; //encoding success and file created
		}

		return false; //encoding failed
	}

		
	
	function updateJsonFile($fname) //fxn to update the json file with new Dirs 
	{
		//build array of subdirectories containing index.php|html, $dirlist
		$dir = getDirList();

		//print_r($dir);
		//echo "i am here";

		$dirlist = array_change_key_case($dir);

		
		//read json file containing currently visible directories/links into array $jsonarray
		$jsonarray = getJsonDir($fname);

		if (!($jsonarray)) {
			$jsonarray = [];
		}

		//find new directories not yet stored in json file
		//find the keys of $dirlist not in $jsonarray and store diff, $diff
		$diff = array_diff_key($dirlist, $jsonarray);

		//add new directories to json file
		//append $diff to $jsonarray
		$jsonarray = array_merge($diff, $jsonarray);

		//remove directories in json file that no longer exist
		//find keys in merged $jsonarray that only exist in $dirlist, $updjsonarray
		$updjsonarray = array_intersect_key($jsonarray, $dirlist);

		//build json file from $updJsonArray 
		$success = buildJsonFile($updjsonarray, $fname);

		return $success;

	}

?>