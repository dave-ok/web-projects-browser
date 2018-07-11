<?php 
	
	session_start();

	//error_reporting(E_ALL);

	include_once('config.php');
	include_once('functions.php');
	include_once('partials/accordion.php');

	
	//create needed directories
	createNeededDirs(['./assets']);

	if (isset($_POST['rebuild']) && ($_POST['rebuild'] == 'yes')) {
		updateJsonFile(JSON_FILE);
		unset($_POST['rebuild']);
		header('location: '. URI_BASE); //redirect to stop page refresh messages
	}
	//if the json file does not exist create and populate it
	if (!(file_exists(JSON_FILE))) {		
		//echo "json file not found";		
		updateJsonFile(JSON_FILE);
	}
	

	//build links to each app directory from json file
	
	//store array of projects and subprojects as SESSION variable
	if (!(isset($_SESSION['projectsArray']))) {
		
		//get project directories from JSON file	
		$_SESSION['projectsArray'] = getJsonDir(JSON_FILE);
	}
	
	$projectsArray = $_SESSION['projectsArray'];

	//decode route
	$projDirs = decodeRoute();

	/* if (!($projDirs)) {
		include_once('404/index.php');
		exit;
	} */

	
	

	//beside each link incude button for copying folder path to clipboard for editors

	/*
	$ldate = getLatestModifiedDate(CURR_DIR, 2);
	echo date("F j, Y, g:i a", $ldate[0]) . '<br>';

	echo "filename is $ldate[1] from " . CURR_DIR . "<br>";
	*/
	
?>		

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pageTitle; ?></title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="http://appdev.local/assets/css/bootstrap.css">

		<!-- Fonts -->
		<link rel="stylesheet" href="http://appdev.local/assets/css/fonts.css">

		<!-- Custom CSS -->
		<link rel="stylesheet" href="http://appdev.local/assets/css/styles.css">		
		<script type="text/javascript" src="http://appdev.local/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="http://appdev.local/assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="http://appdev.local/assets/js/script.js"></script>

		
	</head>
	<body>
		<div class="container-fluid">
			
			<div class="row">				
				<div class="col-md-6 col-md-offset-3">
					<form method="post" action="<?php echo URI_BASE; ?>">
						<h1><?php echo $pageHeader; ?> <span id="refresh"><button>Refresh Dirs</button></span></h1>	
						<input type="hidden" name="rebuild" value="yes">
					</form>
				</div>
				<div class="col-md-3">
					<a href="http://appdev.local" id="home-link"><h4><< Back to Home<h4></a>	
				</div>
			</div>
			<div class="row">
			 					
				<?php 
					//get number of columns
					if (!empty($projDirs)) {
						$chkProjects = array_chunk($projDirs, MAX_ROWS, true);

						$numcols = count($chkProjects); //use this later for paging

						$cntCols = $numcols > MAX_COLS ? MAX_COLS : $numcols; //temp fix w/out paging

						for ($i=0; $i < $cntCols; $i++) { 
							$accordion = createAccordion("accordion$i", $chkProjects[$i], $cntCols);
							echo $accordion;	
						}	
					}
					
					
															
				?>								

						
			</div> <!-- end row -->
			<div class="row">				
				<hr>
				<div class="text-center">
					<?php
						$nav = buildNav($_SESSION['currpage'], MAX_ROWS * MAX_COLS, $_SESSION['totalitems'], $_SESSION['urlbase']);
						echo $nav;
					?>			
				</div>						
			</div>
		</div> <!-- end container-fluid -->
		
		

		
	</body>
</html>
