<?php
/* modal dialog with form for editing - 
	description, title etc. 
Populate with values from
selected item(accordion) in currently filtered array. 
How to identify currently selected item(accordion) in array ???
After editing, saving will post changes to array via ajax. 
On success of writing to array update JSON file with changes.
Update UI in web browser with new values (How???)
*/
include_once('../config.php');
include_once('../functions.php');

$editProjID = '';

if (isset($_GET['projid'])) : //for a get request return the modal dialog ?>

<?php
	$editProjID = $_GET['projid'];

	//from projectsArray get values to prepopulate form

	$projectsArray = getJsonDir($_GET['json-file']);
	$projKey = findValueInArray($editProjID, $projectsArray, 'id');
	$projTitle = isset($projectsArray[$projKey]['title']) ? $projectsArray[$projKey]['title'] : null;
	$projDesc = isset($projectsArray[$projKey]['desc']) ? $projectsArray[$projKey]['desc'] : null;

?>

	<!-- Modal -->
	<div class="modal" id="modal-edit-details" tabindex="-1" role="dialog" aria-labelledby="modal-edit-detailsLabel" aria-hidden="true" data-proj-id="<?php echo $editProjID; ?>">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="modal-edit-detailsLabel">Edit Details</h4>
	      </div>
	      <div class="modal-body">
	        	<form action="" method="POST" role="form" id="edit-form">        		
	        	
	        		<div class="form-group">
	        			<label for="">Title</label>
	        			<input type="text" class="form-control" id="details-title" name="details-title" placeholder="Enter title" value="<?php echo $projTitle; ?>">
	        		</div>
	        		<div class="form-group">
	        			<label for="">Description</label>
	        			<textarea id="details-description" name="details-description" class="form-control" rows="4" placeholder="Enter description"><?php echo $projDesc; ?></textarea>
	        		</div>        	
	        		
	        	</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" id="btn-save">Save</button>
	        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

<?php elseif(isset($_POST['projid'])) : //save changes to file ?> 
	<?php

	$editProjID = $_POST['projid'];
	
	//from projectsArray get projKey
	$projectsArray = getJsonDir($_POST['json-file']);
	$projKey = findValueInArray($editProjID, $projectsArray, 'id');
	$projectsArray[$projKey]['title'] = $_POST['details-title'];
	$projectsArray[$projKey]['desc'] = $_POST['details-description'];

	//write changes back to file 
	buildJsonFile($projectsArray, $_POST['json-file']);

	echo 'Changes saved!';

	?>
<?php endif; ?>