<?php
	getcwd();

?>

<!DOCTYPE HTML>
<html>
<head>
<title>404 error page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://appdev.local/assets/includes/404/css/style.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<!-----start-wrap--------->
	<div class="wrap">
		<!-----start-content--------->
		<div class="content">
			<!-----start-logo--------->
			<div class="logo">
				<h1><a href="http://$_SERVER[HTTP_HOST]"><img src="http://appdev.local/assets/includes/404/images/logo.png"/></a></h1>
				<span><img src="http://appdev.local/assets/includes/404/images/signal.png"/>Oops! The Page you requested was not found!</span>

				<br>
				<span><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>"> << Back to Home</a></span>		
				</div>
			</div>
			<!-----end-logo--------->
			<!-----start-search-bar-section--------->
		<!-- 	<div class="buttom">
				<div class="seach_bar">
					<p>you can go to <span><a href="#">home</a></span> page or search here</p> -->
					<!-----start-sear-box--------->
					<!-- <div class="search_box">
					<form>
					   <input type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}"><input type="submit" value="">
				    </form>
					 </div>
				</div>
			</div> -->
			<!-----end-sear-bar--------->
		<!-- </div> -->
		<!----copy-right-------------->
	
	</div>
	
	<!---------end-wrap---------->
</body>
</html>