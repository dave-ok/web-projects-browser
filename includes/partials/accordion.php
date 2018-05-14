<?php
	/* 
	TO CREATE AN ACCORDION
		Items needed :
			* array containing items to include
			* max number of rows
			* number of columns on page (to determine width)
			* template for accordion 
			* template for column
			* template for  each row
	
	*/		

	function createAccordion($rowItems, $numRows, $pageCols)
	{
		$accordionHeader = ' <div class="panel-group" id="accordion"> <!-- start accordion --> ';

		if ($pageCols > 1) {
			$colSize = 12 / $pageCols;
			$accordionHeader .= "<div class=\"col-md-$colSize\"> <!--begin column --> " ;
		} 
		else {
			$accordionHeader .= '<div class="col-md-6 col-md-offset-3"> <!--begin column --> ' ;
		}

		<div class="panel panel-info"> <!--begin column panel -->						
								<div class="panel-body"> <!-- column panel body -->
	}

?>