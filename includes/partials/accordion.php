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

	function createAccordion($id, $rowItems, $pageCols)
	{
		$pageCols = $pageCols > MAX_COLS ? MAX_COLS : $pageCols; //force maximum cols count of  MAX_COLS

		if ($pageCols > 1) { //divide by number of columns
			$colSize = 12 / $pageCols;
			$accordionHeader = "<div class=\"col-md-$colSize\"> <!--begin column --> " ;
		} 
		else {
			//single column
			$accordionHeader = '<div class="col-md-6 col-md-offset-3"> <!--begin column --> ' ;
		}
		
		$accordionHeader .= ' <div class="panel panel-primary"> <!--begin column panel -->';						
		$accordionHeader .= ' <div class="panel-body"> <!-- column panel body -->';
								
		$accordionHeader .= " <div class=\"panel-group\" id=\"$id\"> <!-- start accordion --> ";

		//iterate thru array $rowItems
		$count = 0;		

		foreach ($rowItems as $project => $values) {										
			$count += 1; 
			$panelItem = '';

			//show title in panel header
			//show description 
			//if path show button to copy file path else show nothing
			//include button for popup modal dialog
			
			//-- start panel item			
			$panelItem .= '<div class="panel panel-info">';

			// panel headings
			$panelItem .= "<div class=\"panel-heading\">
									<h4 class=\"panel-title\">
										<a class=\"item-heading collapsed\" data-toggle=\"collapse\" data-parent=\"#$id\" href=\"#collapse$id$count\">
										          $project		          
										</a>
										
									</h4>
								</div>";

			// show in new tab if its an actual link
			$target = '';									      			
			if (!(isset($values['is_parent']))) {
   			$target = ' target="_blank"';
   		 
   		}
   		// panel collapse item
			$panelItem .= "<div id=\"collapse$id$count\" class=\"panel-collapse collapse\">";

			//panel body
			$panelItem .= "<div class=\"panel-body\">											   
								   <a class=\"list-group-item-text proj-link\" href=\"$values[url]\" $target>$values[url]</a>
								   <a class=\"btn btn-info btn-xs pull-right proj-link\" href=\"$values[url]\"><span class=\"glyphicon glyphicon-new-window\"></span> Go</a>
									<div class=\"clearfix\"></div>
									<hr class=\"proj-rule\">
									<p class=\"proj-description\"> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
									</p>
								</div>";
			//end panel body

			//panel footer
			$panelItem .= "<div class=\"panel-footer proj-footer clearfix\">
									<a class=\"btn btn-warning btn-sm proj-link pull-left\" href=\"#\" title=\"Edit description\"><span class=\"glyphicon glyphicon-edit\"></span> Edit</a>
									<a class=\"btn btn-primary btn-sm btn-copy pull-right\" href=\"#\" title=\"$values[path]\" data-path=\"$values[path]\" data-toggle=\"tooltip\"><span class=\"glyphicon glyphicon-copy\"></span> Copy</a>
								</div>";

			//end panel footer

			//end panel collapse item
			$panelItem .= '</div>';					
			// end panel item
			$panelItem .= '</div>';

			//add panel item to accordion
			$accordionHeader .= $panelItem;
			
		}

		//close the accordion tags
		$accordionHeader .= '</div> <!-- end accordion -->
		</div> <!-- end column panel body -->
		</div> <!-- end column panel -->
		</div> <!-- end column -->';

		return $accordionHeader;
	}	

?>