<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: add.php
//
// Description: Processes newly created
// entities by adding them to the database
// via the web service API.
// ---------------------------------------

	include "utilities.php";

	session_start();

	// Retrieve new record data
	$newBusinessName = cleanString($_POST['newBusinessName']);
	$newBusinessWebsite = cleanString($_POST['newBusinessWebsite']);
	$newBusinessPhone = cleanString($_POST['newBusinessPhone']);
	$newBusinessAddress = cleanString($_POST['newBusinessAddress']);
	$newBusinessHours = cleanString($_POST['newBusinessHours']);
	if($_POST['newBusinessRepairs'] == "on")
	    $newBusinessRepairs = "1";
	else
	    $newBusinessRepairs = "0";
	$newCategoryName = cleanString($_POST['newCategoryName']);
	$newItemName = cleanString($_POST['newItemName']);

	// Determine which table should receive the new entity and call the web service
	if($newBusinessName != "")
	{
		$data = "name=$newBusinessName&website=$newBusinessWebsite&phone=$newBusinessPhone&address=$newBusinessAddress&hours=$newBusinessHours&isRepair=$newBusinessRepairs";
		
		// Retrieve the latitude and longitude of the new business
		$latLong= call('GET', "https://maps.googleapis.com/maps/api/geocode/json?address=".rawurlencode($newBusinessAddress)."&key="); //Insert API key here
		
		$jsonObject = json_decode($latLong,true);
	    $data .= "&lat=".$jsonObject['results'][0]['geometry']['location']['lat'];
	    $data .= "&long=".$jsonObject['results'][0]['geometry']['location']['lng'];
		
		$response = call('POST','http://charlesmjenkins.com/reuse/webservice/api.php/business?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2', $data);

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		die();
	}
	else if($newCategoryName != "")
	{
		$data = "name=$newCategoryName";
		$response = call('POST','http://charlesmjenkins.com/reuse/webservice/api.php/category?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2', $data);

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		die();
	}
	else if($newItemName != "")
	{
		$data = "name=$newItemName";
		$response = call('POST','http://charlesmjenkins.com/reuse/webservice/api.php/item?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2', $data);

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		die();
	}
	else if(isset($_SESSION['username']))
	{
		header('Location: admin.php');
		die();
	}
	else if(!(isset($_SESSION['username'])))
	{
		header('Location: login.php');
		die();
	}
?>