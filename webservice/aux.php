<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: aux.php
//
// Description: Specialized auxiliary API
// used to process entity relation edit
// requests to the database.
// ---------------------------------------
// Acknowledgement:
// https://davidwalsh.name/web-service-php-mysql-xml-json

if(isset($_GET['authCode']) && $_GET['authCode'] == "934mh5Df4X" && isset($_GET['resetID']) && isset($_GET['type'])) {

	// Get arguments
	$resetId = $_GET['resetID'];
	$newRelations = explode(",", $_GET['newRelations']);
	$type = $_GET['type'];

	// Connect to database
	$dbhost = ''; // Hostname goes here
	$dbname = ''; // Database name goes here
	$dbuser = ''; // Username goes here
	$dbpass = ''; // Password goes here
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname); 
	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	  exit;
	}
	
	if($type == "businessItems"){
		// Delete all references to this business in item-business table
		$query = "DELETE FROM `item-business` WHERE bid=$resetId";
		$result = $mysqli->query($query) or die('SQL syntax error: ' . mysql_error());
	
		// Insert new list of item relations for this business
		$query = "INSERT INTO `item-business` (iid, bid) VALUES ";
		
		$numberOfItems = count($newRelations);
		$counter = 1;
		foreach($newRelations as $iid){
			$query .= "($iid, $resetId)";
			
			if($counter < $numberOfItems)
				$query .= ", ";
			else
				$query .= ";";
				
			$counter += 1;
		}
	}
	elseif($type == "itemCategories"){
		// Delete all references to this category in item-category table
		$query = "DELETE FROM `item-category` WHERE iid=$resetId";
		$result = $mysqli->query($query) or die('SQL syntax error: ' . mysql_error());
	
		// Insert new list of item categories for this item
		$query = "INSERT INTO `item-category` (iid, cid) VALUES ";
		
		$numberOfCategories = count($newRelations);
		$counter = 1;
		foreach($newRelations as $cid){
			$query .= "($resetId, $cid)";
			
			if($counter < $numberOfCategories)
				$query .= ", ";
			else
				$query .= ";";
				
			$counter += 1;
		}
	}
	
	$result = $mysqli->query($query) or die('SQL syntax error: ' . mysql_error());

}
?>