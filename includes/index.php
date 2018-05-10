<?php 
	
	//session_start();

	include_once('functions.php');

	if (isset($_POST['rebuild']) && ($_POST['rebuild'] == 'yes')) {
		updateJsonFile(JSON_FILE);
		$_POST['rebuild'] = '';
	}
	//if the json file does not exist create and populate it
	if (!(file_exists(JSON_FILE))) {		
		echo "json file not found";		
		updateJsonFile(JSON_FILE);
	}

	//set maxRows and maxColumns
	$maxRows = 5;
	$maxColumns = 2;

	//build links to each app directory from json file
	//get directories list
	$projDirs = getJsonDir(JSON_FILE);

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

		
	</head>
	<body>
		<div class="container-fluid">
			
			<div class="row">				
				<div class="col-md-6 col-md-offset-3">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
					//if number items in array is < 4 use one column
					//else use two
				?>							
				<?php 
					$count = 0; 
					$colCount = 0;
					$rowCount = 0;

					$numItems = count($projDirs);
										
				?>
				<div class="panel-group" id="accordion"> <!-- start accordion -->
					<div class="col-md-6 col-md-offset-3"> <!--begin column -->
						<div class="panel panel-info"> <!--begin column panel -->						
								<div class="panel-body"> <!-- column panel body -->
								<?php foreach ($projDirs as $project => $values) : ?>
									<?php	
										/*
										$count += 1; 
										
										if ($rowCount == 0) { //if 1st row or rows complete wrap to next col
											$colCount += 1;
											if ($colCount > $maxColumns) {
												break; //leave foreach loop
											}
											//add new column/accordion							
										}
										*/
										//wrap to next column
										//if columns(count/maxItemsPerColumn) has reached maxColumns break
									?>						
										<!-- create panel item -->
										<div class="panel panel-info">
									    	<div class="panel-heading">
										      <h4 class="panel-title">
										        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>">
										          <?php echo $project; ?>
										        </a>
										      </h4>
									    	</div>
									    	<div id="collapse<?php echo $count; ?>" class="panel-collapse collapse">
									      	<div class="panel-body">
									        		<p class="list-group-item-text"><?php echo $values['path']; ?></p>
									      	</div>
									    	</div>
									  	</div>					
								  		
									
								<?php endforeach; ?>
							</div> <!-- end column panel body -->
						</div> <!-- end column panel -->
					</div> <!-- end column -->
				</div> <!-- end accordion -->	
							

						
			</div> <!-- end row -->
		</div> <!-- end container-fluid -->
		
		

		
	</body>
</html>
