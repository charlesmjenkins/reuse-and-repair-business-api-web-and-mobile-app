<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: delete.php
//
// Description: Processes deleted
// entities by removing them from the 
// database via the web service API.
// ---------------------------------------

	include "utilities.php";

	session_start();

	// Get ID of record to delete
	$bid = cleanString($_GET['bid']);
	$cid = cleanString($_GET['cid']);
	$iid = cleanString($_GET['iid']);

	// Determine table from which the ID should be deleted and call the web service
	if($bid != "")
	{
		$data = "id=$bid";
		$response = call('DELETE',"http://charlesmjenkins.com/reuse/webservice/api.php/business/$bid?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		die();
	}
	if($cid != "")
	{
		$data = "id=$cid";
		$response = call('DELETE',"http://charlesmjenkins.com/reuse/webservice/api.php/category/$cid?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		die();
	}
	if($iid != "")
	{
		$data = "id=$iid";
		$response = call('DELETE',"http://charlesmjenkins.com/reuse/webservice/api.php/item/$iid?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2");

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