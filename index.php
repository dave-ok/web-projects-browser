<?php 
	//read json file and parse
	$app_dirs = json_decode(file_get_contents('./assets/js/data.json'));
	//print_r($app_dirs);

?>

<!DOCTYPE html>
<html>
<head>
	<title>WebDev Base</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>Web Development Base</h1>

				<h3>Choose your destiny !!!</h3>
				
				<div class="list-group">
					<?php foreach ($app_dirs as $platform => $values) : ?>
						<a href="<?php echo $values->url; ?>" class="list-group-item list-group-item-info">
							<div>
								<img src="<?php echo $values->icon ; ?>" class="list-image pull-right" alt="Image">
								<h3 class="list-group-item-heading"><?php echo $platform; ?></h3>
								<p class="list-group-item-text"><?php echo $values->description; ?></p>					
								
							</div>							
							
						</a>
					<?php endforeach; ?>
					
					
				</div>

				
			</div>		
		
		</div>		
	</div>
	
</body>
</html>
