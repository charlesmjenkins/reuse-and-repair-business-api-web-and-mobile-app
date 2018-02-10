<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: admin.php
//
// Description: Main admin page allowing for
// full CRUD operations on the reuse & recycle
// data used to power the accompanying Android
// app.
// ---------------------------------------
// Acknowledgement:
// http://www.jqueryajaxphp.com/live-table-edit-using-jquery-ajax-and-php-twitter-bootstrap/

	session_start();

	include "utilities.php";

	if(!isLoggedIn())
	{
		header('Location: login.php');
		die();
	}

	$username = $_SESSION['username'];
?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Corvallis Sustainable Coalition: Reuse & Repair Directory Admin Portal</title>
		
		<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
		
		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/main2.css">
		
		<!-- Google Icons (delete trash icon) -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		
		<!-- jQuery: https://jquery.com/ -->
		<script src="js/jquery.min.js"></script>
		
		<!-- Bootstrap CSS: https://getbootstrap.com/ -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
		<!-- Bootswatch Yeti Theme CSS: https://bootswatch.com/ -->
		<link rel="stylesheet" type="text/css" href="css/yeti.css">
		
		<!-- Bootstrap JS: https://getbootstrap.com/ -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		
		<!-- select2: https://select2.github.io/ -->
		<link href="select2-stable-3.5/select2.css" rel="stylesheet" type="text/css"></link>
		<script type="text/javascript" src="select2-stable-3.5/select2.js"></script>
		<link href="css/select2-bootstrap.css" rel="stylesheet" type="text/css"></link>
		
		<!-- DataTables: https://datatables.net/ -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
		<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
		
		<!-- X-editable: https://vitalets.github.io/x-editable/ -->
		<link href="bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
		<script src="bootstrap3-editable/js/bootstrap-editable.js"></script>
		
		<!-- jQuery-Confirm: http://craftpip.github.io/jquery-confirm/ -->
		<link href="confirm/jquery-confirm.min.css" rel="stylesheet">
		<script src="confirm/jquery-confirm.min.js"></script>
		
		<!-- Tooltipster: http://iamceege.github.io/tooltipster/ -->
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				// Configure x-editable defaults
				$.fn.editable.defaults.mode = 'inline';
    			$.fn.editable.defaults.highlight = "#DFF0D8";
    			$.fn.editable.defaults.emptytext = '';
    			$.fn.editable.defaults.display = 'false';
    			
    			<?php
    				// Get joined item and category data; populate business items select2 options
				    $responseItems = call('GET','http://charlesmjenkins.com/reuse/webservice/api.php/item,category?transform=1');
	                $jsonObjectItems = json_decode($responseItems,true);
	                
	                $numberOfItems = count($jsonObjectItems['item']);
                	$counter = 1;
                	
                	echo "var itemsArray = [";
	                foreach($jsonObjectItems['item'] as $item) {
        		        echo "{id: '".$item["id"]."', text: '".$item["name"]."'}";
        		        
        		        if($counter != $numberOfItems)
    		        		echo ", ";
    		        		
		        		$counter += 1;
    		        }
    		        echo "];";
    		        
    		        // Get joined category data; populate item categories select2 options
    		        $responseCategories = call('GET','http://charlesmjenkins.com/reuse/webservice/api.php/category');
                	$jsonObjectCategories = json_decode($responseCategories,true);
                	
                	$numberOfCategories = count($jsonObjectCategories['category']['records']);
                	$counter = 1;
                	
                	echo "var categoriesArray = [";
	                foreach($jsonObjectCategories['category']['records'] as $item) {
        		        echo "{id: '".$item[0]."', text: '".$item[1]."'}";
        		        
        		        if($counter != $numberOfCategories)
    		        		echo ", ";
    		        		
		        		$counter += 1;
    		        }
    		        echo "];";
			    ?>
				
				// Configure Business DataTable
    			$('#businessTable').DataTable({
    				// Ensure all pages of data are editable
    				"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						$('.xedit').editable();
						$('.isRepairChecklist').editable({
					        type: 'select',
						    source: {'0': 'No', '1': 'Yes'},
						    autotext: 'always'
					    });
					    $('.businessItems').editable({
						    source: itemsArray,
						    emptytext: 'None',
					        select2: {
					        	toggle: 'show',
					            multiple: true,
					            width: 300,
					            allowClear: true,
					        } 
					    });
					    $('[data-underline="false"]').css('border-bottom', 'none');
    				},
    				"order": [[ 1, "asc" ]],
    				"aoColumnDefs": [
				       { 'bSortable': false, 'aTargets': [ 0 ] }
				    ]
				});
				
				// Configure Items DataTable
    			$('#itemTable').DataTable({
    				// Ensure all pages of data are editable
    				"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						$('.xedit').editable();
						$('.itemCategories').editable({
						    source: categoriesArray,
						    emptytext: 'None',
					        select2: {
					        	toggle: 'show',
					            multiple: true,
					            width: 300,
					            allowClear: true,
					        } 
					    });
					    $('[data-underline="false"]').css('border-bottom', 'none');
    				},
    				"order": [[ 1, "asc" ]],
    				"aoColumnDefs": [
				       { 'bSortable': false, 'aTargets': [ 0 ] }
				    ]
    			});
    			
    			// Configure Category DataTable
    			$('#categoryTable').DataTable({
    				// Ensure all pages of data are editable
    				"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						$('.xedit').editable();
						$('[data-underline="false"]').css('border-bottom', 'none');
    				},
    				"order": [[ 1, "asc" ]],
    				"aoColumnDefs": [
				       { 'bSortable': false, 'aTargets': [ 0 ] }
				    ]
    			});
    			
    			// Configure general editable fields
    			$('.xedit').editable();
 
 				// Configure business items editable select2 fields
			    $('.businessItems').editable({
				    source: itemsArray,
				    emptytext: 'None',
			        select2: {
			        	toggle: 'show',
			            multiple: true,
			            width: 300,
			            allowClear: true,
			        } 
			    });
			    
			    // Configure item categories editable select2 fields
			    $('.itemCategories').editable({
				    source: categoriesArray,
				    emptytext: 'None',
			        select2: {
			        	toggle: 'show',
			            multiple: true,
			            width: 300,
			            allowClear: true,
			        } 
			    });
			    
			    // Configure isRepair editable checklist
			    $('.isRepairChecklist').editable({
			        type: 'checklist',
				    source: {'1': 'Yes'},
				    autotext: 'always'
			    });
			    
			    // Disable automatic underlining of editable data
			    $('[data-underline="false"]').css('border-bottom', 'none');
			    
			    // Enable trash icons to launch jQuery confirmation dialogue
			    // and process delete requests
			    $(document).on('click','.trashButton',function(){
			    	var id = $(this).attr('id');
			    	var table = $(this).attr('table');
			    	
					$.confirm({
					    title: 'Confirm!',
					    content: 'Are you sure you want to delete this record?',
					    confirm: function(){
					    	if(table == "business")
					    		window.location.replace("delete.php?bid="+id);
				    		if(table == "category")
					    		window.location.replace("delete.php?cid="+id);
				    		if(table == "item")
					    		window.location.replace("delete.php?iid="+id);
					    }
					});
				});
			    
			    // On submission of inline edits, pass new data to process.php for asynchronous updating via web service 
    			$(document).on('click','.editable-submit',function(){
					var table = $(this).closest('.editable-container').prev().attr('table');
					var key = $(this).closest('.editable-container').prev().attr('key');
					var x = $(this).closest('.editable-container').prev().attr('id');
					var y = $('.input-sm').val();
					var itemsList = $(this).closest('span').text();
					var z = $(this).closest('.editable-container').prev().text(y);
					
					$.ajax({
						url: "process.php?id="+x+"&data="+y+'&key='+key+'&table='+table+'&itemsList='+itemsList,
						type: 'GET',
						success: function(s){
							if(s == 'status'){
							$(z).html(y);}
							if(s == 'error') {
							alert('Error Processing your Request!');}
						},
						error: function(e){
							$.alert({
							    title: 'Change not saved!',
							    content: "Make sure you are not leaving a business, item, or category's name field blank. They are required!"
							});
						}
					});
				});
			} );	
			
		</script>
	</head>

	<body>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <span class="pull-left"><img src="img/logox50h.jpg" alt="Corvallis Sustainable Coalition Logo"></span> <span class="navbar-brand">Reuse & Repair Directory Admin Portal</span>
		    </div>
		    <ul class="nav navbar-nav navbar-right">
		      <li class="active"><a><?php echo $username; ?></a></li>
		      <li class="active"><a href="logout.php">Log Out</a></li>
		    </ul>
		  </div>
		</nav>
		
		<div id="content" class="center-block">
			
			<div class="centered">
				
			<div class="col-xs-12">
			<h3>Businesses</h3>
				<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#addBusinessForm">+ Add New Business</button>
				<div id="addBusinessForm" class="collapse">
				<form class="form col-xs-3" role="form" method="POST" action="add.php">
				  <div class="form-group">
				    <label for="newBusinessName">Name:*</label>
				    <input type="text" class="form-control" id="newBusinessName" name="newBusinessName" maxlength="100" required>
				  </div>
				  <div class="form-group">
				    <label for="newBusinessWebsite">Website:</label>
				    <input type="text" class="form-control" id="newBusinessWebsite" name="newBusinessWebsite" maxlength="255">
				  </div>
				  <div class="form-group">
				    <label for="newBusinessPhone">Phone:</label>
				    <input type="text" class="form-control" id="newBusinessPhone" name="newBusinessPhone" maxlength="30">
				  </div>
				  <div class="form-group">
				    <label for="newBusinessAddress">Address:</label>
				    <input type="text" class="form-control" id="newBusinessAddress" name="newBusinessAddress" maxlength="255">
				  </div>
				  <div class="form-group">
				    <label for="newBusinessHours">Hours:</label>
				    <input type="text" class="form-control" id="newBusinessHours" name="newBusinessHours" maxlength="255">
				  </div>
				  <div class="checkbox">
				    <label><input type="checkbox" id="newBusinessRepairs" name="newBusinessRepairs">Performs Repairs?</label>
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>
				</div>
					
    		    <div class="">
			        <table class="table table-hover table-condensed table-striped text-nowrap" class="outerTable" id="businessTable">
			            <thead>
				            <tr>
				                <th class=""></th>
				                <th class="">Name</th>
				                <th class="">Website</th>
				                <th class="">Phone</th>
				                <th class="">Address</th>
				                <th class="">Hours</th>
				                <th class="">Repairs?</th>
				                <th class="">Items</th>
				            </tr>
			            </thead>
			            <tbody>
			            	
            			<?php
            				// Build Business table based on data from web service call
            		        $response = call('GET','http://charlesmjenkins.com/reuse/webservice/api.php/business,item?transform=1');
                	    	$jsonObject = json_decode($response,true);
                	    	
                		    foreach($jsonObject['business'] as $business) {
                		        echo "<tr class='browseRow'><td><a table='business' id='".$business["id"]."' class='btn btn-danger btn-link btn-block trashButton'><i class='material-icons'>delete_forever</i></a></td>"."<td data-underline='false' style='cursor:pointer' class='xedit required' id='".$business["id"]."' key='name' table='business'>".$business["name"]."</td>";
                		        echo "<td data-underline='false' style='cursor:pointer' class='xedit' id='".$business["id"]."' key='website' table='business'><div class='truncateWebsite'>".$business["website"]."</div></td>"."<td data-underline='false' style='cursor:pointer' class='xedit' id='".$business["id"]."' key='phone' table='business'>".$business["phone"]."</td>";
                		        echo "<td data-underline='false' style='cursor:pointer' class='xedit' id='".$business["id"]."' key='address' table='business'>".$business["address"]."</td>"."<td data-underline='false' style='cursor:pointer' class='xedit' id='".$business["id"]."' key='hours' table='business'><div class='truncateHours'>".$business["hours"]."</div></td>";
                		        echo "<td data-underline='false' style='cursor:pointer' class='isRepairChecklist' id='".$business["id"]."' key='isRepair' table='business' data-value='".$business["isRepair"]."'>".$business["isRepair"]."</td><td><div class='truncateItems'>";
                		        
                		        echo "<span data-underline='false' key='itemList' table='business' id='".$business["id"]."' style='cursor:pointer' class='businessItems' data-type='select2' data-value='";
                		        
                		        // Prepopulate items already related to each business record
                		        $numberOfItems = count($business["item-business"]);
                		        $counter = 1;
                		        foreach($business["item-business"] as $item){
                		        	echo $item["item"][0]["id"];
                		        	
                		        	if($counter != $numberOfItems)
                		        		echo ", ";
                		        		
            		        		$counter += 1;
                		        }
                		        
                		        echo "' ></span></div></td></tr>";
            		        }
            			?>
            			</tbody>
        			</table>
    			</div>
    		</div>
			
			<div class="row"></div>
			<div class="col-xs-6">
			<h3>Categories</h3>
				<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#addCategoryForm">+ Add New Category</button>
				<div id="addCategoryForm" class="collapse">
				<form class="form col-xs-3" role="form" method="POST" action="add.php">
				  <div class="form-group">
				    <label for="newCategoryName">Name:*</label>
				    <input type="text" class="form-control" id="newCategoryName" name="newCategoryName" maxlength="100" required>
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>
				</div>
			
			    <div class="">
			        <table class="table table-hover table-condensed table-striped text-nowrap" class="outerTable" id="categoryTable">
			            <thead>
				            <tr>
				                <th class="nameCell"></th>
				                <th class="nameCell">Name</th>
				            </tr>
			            </thead>
			            <tbody>
            			<?php
            				// Build Category table based on data from web service call
                		    foreach($jsonObjectCategories['category']['records'] as $item) {
                		        echo "<tr class='browseRow' id='".$item[0]."'><td><a table='category' id='".$item[0]."' class='btn btn-danger btn-link btn-block trashButton'><i class='material-icons'>delete_forever</i></a></td>"."<td data-underline='false' style='cursor:pointer' class='xedit required' id='".$item[0]."' key='name' table='category'>".$item[1]."</td></tr>";
            		        }
            			?>
            			</tbody>
        			</table>
    			</div>
    			</div>
    			
    		<div class="col-xs-6">
			<h3>Items</h3>
				<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#addItemForm">+ Add New Item</button>
				<div id="addItemForm" class="collapse">
				<form class="form col-xs-3" role="form" method="POST" action="add.php">
				  <div class="form-group">
				    <label for="newItemName">Name:*</label>
				    <input type="text" class="form-control" id="newItemName" name="newItemName" maxlength="100" required>
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>
				</div>
			
    		    <div class="">
			        <table class="table table-hover table-condensed table-striped text-nowrap" class="outerTable" id="itemTable">
			        	<thead>
			            	<tr>
			                	<th class="nameCell"></th>
			                	<th class="nameCell">Name</th>
			                	<th class="nameCell">Categories</th>
			            	</tr>
			            </thead>
			            <tbody>
            			<?php
            				// Build Item table based on data from web service call
                		    foreach($jsonObjectItems['item'] as $item) {
                		        echo "<tr class='browseRow' id='".$item["id"]."'><td><a table='item' id='".$item["id"]."' class='btn btn-danger btn-link btn-block trashButton'><i class='material-icons'>delete_forever</i></a></td>"."<td data-underline='false' style='cursor:pointer' class='xedit required' id='".$item["id"]."' key='name' table='item'>".$item["name"]."</td><td>";
                		        
                		        echo "<span data-underline='false' key='itemList' table='item' id='".$item["id"]."' style='cursor:pointer' class='itemCategories' data-type='select2' data-value='";
                		        
                		        // Prepopulate categories already related to each item record
                		        $numberOfCategories = count($item["item-category"]);
                		        $counter = 1;
                		        foreach($item["item-category"] as $category){
                		        	echo $category["category"][0]["id"];
                		        	
                		        	if($counter != $numberOfCategories)
                		        		echo ", ";
                		        		
            		        		$counter += 1;
                		        }
                		        
                		        echo "' ></span></td></tr>";
            		        }
            			?>
            			</tbody>
        			</table>
    			</div>
    			</div>
    			</div>
			</div>
		</div>
	</body>
</html>