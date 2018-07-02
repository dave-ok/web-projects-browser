<?php	
	function createNeededDirs($dirsNeeded)
	{
		//create directories that we need		
		$lenDirs = count($dirsNeeded);

		for ($i=0; $i < $lenDirs; $i++) { 
			if (!file_exists($dirsNeeded[$i]) && !is_dir($dirsNeeded[$i])) {
		   	mkdir($dirsNeeded[$i]);         
			} 	
		}	
	}

	function displayArray($array)
	{
		if (is_array($array)) {
			echo "<br>";
			echo "<pre>";
			print_r($array);
			echo "</pre>";
			echo "<br>";	
		}
		else {
			echo "<br>not array<br>";
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

	function recursiveDiff() {
     $arrs = func_get_args();
     $first = array_shift($arrs);
     $diff = [];

     foreach($first as $key => $value) {
         $values = array_map(function($arr) use($key){
             if (is_array($arr) && !array_key_exists($key, $arr))
                 return null;

             return $arr[$key];
         }, $arrs);

         if (in_array($value, $values))
             continue;

         if (is_array($value)) {
             array_unshift($values, $value);
             $diff[$key] = call_user_func_array(__FUNCTION__, $values);
             continue;
         }

         $diff[$key] = $first[$key];
     }

     return $diff;
 }


	function getLatestModifiedDate($dir, $maxDepth = MAX_MODIFIED_DEPTH, $latestModDate = [ 0, ''], $currDepth = 0) {    
	   
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

	function getDirList($currDir = CURR_DIR, $maxDepth = MAX_SUBS_DEPTH, $setModifiedTimes = false, $currDepth = 0, $parent = '', $parentPath = URI_BASE)
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
				$url = $parentPath . $fname . '/';

				//echo "$url <br>";
				
				if (file_exists($dirPath . '/.leaveme')) 
				{
					//do nothing leave it alone
				}
				elseif (file_exists($baseName . 'php') || file_exists($baseName . 'html')) 
				//if found stop looking
				{		 			
					//if found add directory and win path to dirList
					//convert linux path to windows path
					//$fname = strtolower($fname);

					$dirList[$fname]['path'] =  $winDir . $fname;

					//echo $dirList[$fname]['path'] . '<br>';		 				 			
					
					$dirList[$fname]['url'] = $url;
					$dirList[$fname]['depth'] = $currDepth;
					$dirList[$fname]['parent'] = $parent;
					$dirList[$fname]['is_parent'] = false;
					 

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
								 				
						$returnedList = getDirList($dirPath, $maxDepth, $setModifiedTimes, $currDepth, $fname, $url);

						//if the returned array is not empty, it is a parent. Add to dirList
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
							//$dirList[$fname]['url'] = '#';
							$dirList[$fname]['url'] = $url;

							//echo "$url <br>";

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
		if (file_exists($fname)) {
			$jsonDir = file_get_contents($fname);
			if ($jsonDir) {
				$dirArr = json_decode(strtolower($jsonDir), true);			
			}
		}
		

		return $dirArr;
	}

	function buildJsonFile($dirArray, $fname) //build json file from array of directories
	{
		$jsonDir = json_encode($dirArray, JSON_FORCE_OBJECT);

		if ($jsonDir) {
			//echo $fname . '<br>';
			if (file_exists($fname)) {
				file_put_contents($fname, '');
			}
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

		//-----------------------------------------------------------------------------------
		//TO-DO convert section to function
		//use function to recursively check difference in subarrays
		//up to maxDepth

		//find new directories not yet stored in json file
		//find the keys of $dirlist not in $jsonarray and store diff, $diff
		$diff = recursiveDiff($dirlist, $jsonarray);
		//displayArray($dirlist);
		//displayArray($jsonarray);
		//displayArray($diff);

		//add new directories to json file
		//append $diff to $jsonarray
		$jsonarray = array_replace_recursive($jsonarray, $diff);

		//remove directories in json file that no longer exist
		//find keys in merged $jsonarray that only exist in $dirlist, $updjsonarray
		$updjsonarray = array_intersect_key($jsonarray, $dirlist);

		//---------------------------------------------------------------------------------

		//build json file from $updJsonArray 
		$success = buildJsonFile($updjsonarray, $fname);

		//clear session variable containing projects array since we just rebuilt JSON FILE
		unset($_SESSION['projectsArray']);

		return $success;

	}

	function decodeRoute($url = URI_PATH) //function to get parts from URL
	{
		$validPath = preg_match(REGEX_URL, $url, $urlParts);

		//need to return pagination info
		//pgnumber, total_items, items_per_page


		if ($validPath) {

			//displayArray($urlParts);

			//extract pageNumber
			$pageNumber = $urlParts['pgnum'];

			//if pageNumber is 0 ('') it means first page
			$pageNumber = $pageNumber === '' ? 1 : $pageNumber;
			
			//assign $pagenumber to session var to make it accessible by other functions
			$_SESSION['currpage'] = $pageNumber;

			//extract urlparts
			$urlpath = $urlParts['urlpath'];

			//check if url is taxonomy or path	
			$istax = $urlParts['istax'] === 'txm';

			preg_match(REGEX_BASE, $url, $urlbase);
			$_SESSION['urlbase'] = rtrim($urlbase['urlbase'], '/');			

			if ($istax) {
				return filterTaxArray($urlpath, $pageNumber);
			} else {
				return filterPathArray($urlpath, $pageNumber);
			}
			
		}
		
		return false; //goto 404 error page
		
	}

	function filterSubArray($array, $fkey, $fvalue, $opr)
	{
		$f_array = array_filter(
				$array,
				function ($value)
				use($fkey, $fvalue, $opr)
				{
				 	if (is_array($value)) {

				 		if (array_key_exists($fkey, $value)) {
				 			
				 			if ($opr == 'eq') {
				 				return $value[$fkey] === $fvalue;				 				
				 			}				 			
				 			elseif ($opr == 'neq') {
				 				return $value[$fkey] !== $fvalue;
				 			}
				 			elseif ($opr == 'str') {				 				
				 				return stripos($value[$fkey], $fvalue) !== false;
				 			}
				 			elseif ($opr == 'lt') {
				 				return $value[$fkey] < $fvalue;	
				 			}
				 			elseif ($opr == 'gt') {
				 				return $value[$fkey] > $fvalue;	
				 			}
				 			elseif ($opr == 'subset') {
				 				# code...
				 			}
				 			else {
				 				return false;
				 			}
				 		}
				 	}
				} 
			);
		
		return $f_array;
	}

	function filterPathArray($urlpath, $pageNum)
	{			
		if (!(isset($_SESSION['projectsArray']))) {
			return false;
		}

		$filteredArray = $_SESSION['projectsArray'];


		//if urlpath is '' no filtering required
		if ($urlpath === '') {
			$filteredArray = filterSubArray($filteredArray, 'depth', 1, 'eq');
		}
		else {
			
			// filter path array on elements having parent
			$filteredArray = filterSubArray($filteredArray, 'has_parent', true, 'eq');

			// filter path array on elements having 'url' element with value containing URL
			$filteredArray = filterSubArray($filteredArray, 'url', URI_PATH, 'str');
			

			// filter path array on elements having depth = numParts
			$urlparts = explode('/', $urlpath);
			$numparts = count($urlparts);
			$filteredArray = filterSubArray($filteredArray, 'depth', $numparts + 1, 'eq');

		}

		//session variable to store total item after filtering
		$_SESSION['totalitems'] = count($filteredArray);

		// filter path array by current page number
		$offset = ($pageNum - 1) * MAX_COLS * MAX_ROWS;
		$filteredArray = array_slice($filteredArray, $offset, MAX_COLS * MAX_ROWS, true);


		return empty($filteredArray) ? false : $filteredArray;

	}

	function filterTaxArray($urlpath, $pageNum)
	{
		//filter array based on taxononomy values in $taxArray
		//array could consist of various type of taxonomies
	}

	function buildNav($currpage, $items_per_page, $total_items, $urlbase)
	{
		/*
		 o/p needed
		 totalpages
		 visible pg nos. (max 3)
		 prev link and next link
		 first and last link

		*/
		 $totalpages = ceil($total_items/$items_per_page);

		 if ($totalpages <= 1) { //no need for nav if page is one
		 	return '';
		 }

		 if ($currpage <= 1) {
		 	$prevpage = false;
		 } 
		 else {
		 	$prevpage = $currpage - 1;
		 }

		 if ($currpage >= $totalpages) {
		 	$nextpage = false;
		 } 
		 else {
		 	$nextpage = $currpage + 1;
		 }

		 $vislinks = VISIBLE_LINKS > $totalpages ? $totalpages : VISIBLE_LINKS;

		 if (($currpage - $vislinks) <= 0) {
		 	$startno = 1;
		 }
		 elseif (($currpage + $vislinks) > $totalpages) {
		 	$startno = $totalpages + 1 - $vislinks;
		 }
		 else {
		 	$startno = $currpage - ceil($vislinks/2);
		 }

		 //build nav
		 $strnav = '<ul class="pagination">';
		 $strnav .= $prevpage ?  "<li><a href=\"$urlbase/$prevpage\">&laquo;</a></li>":'<li class="disabled"><span>&laquo;</span></li>';

		 //build inner links
		 $lastlink = $startno + $vislinks;
		 for ($i=$startno; $i < $lastlink; $i++) { 
		 	if ($i==$currpage) {
		 		$strnav .= "<li class=\"active\"><a href=\"$urlbase/$i\">$i</a></li>";
		 	} else {
		 		$strnav .= "<li><a href=\"$urlbase/$i\">$i</a></li>";
		 	}		 	
		 }

		 $strnav .= $nextpage ? "<li><a href=\"$urlbase/$nextpage\">&raquo;</a></li>":'<li class="disabled"><span>&raquo;</span></li>';
		 $strnav .= '</ul>';
		 

		 $firstpage = 1;
		 $lastpage = $totalpages;

		 return $strnav;
		 
		 
	}

?>