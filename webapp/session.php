<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: session.php
//
// Description: Processes login and account
// creation requests from the login page.
// ---------------------------------------

	include "utilities.php";

	session_start();

	// Retrieve data from form
	$email1 = cleanString($_POST['email1']);
	$email2 = cleanString($_POST['email2']);
	$password1 = cleanString($_POST['password1']);
	$username2 = cleanString($_POST['username2']);
	$password2 = cleanString($_POST['password2']);
	$returningUser = $_POST['returningUser'];
	$newUser = $_POST['newUser'];
	$salt = "WorkWorkJobsDone";
	$incorrectAlready = 0;
	
	$output = "";

	// Determine if a session needs to be created
	// and where to redirect
	if($returningUser)
	{
		// Retrieve user data via web service
		$response = call('GET','http://charlesmjenkins.com/reuse/webservice/api.php/admin?filter=email,eq,'.$email1.'&appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2');
		$jsonObject = json_decode($response,true);
		foreach($jsonObject['admin']['records'] as $item) {
		    $output = $jsonObject['admin']['records'][0][1];
		    $output = $output."<br>";
		    $output = $output.$jsonObject['admin']['records'][0][2];
		    $output = $output."<br>";
		    $output = $output.$jsonObject['admin']['records'][0][3];
		}
		
		// Check whether account exists with provided email
		if($output === ""){
			$output = "No account exists with that email.";
			echo $output;
			die();
		}
		
		// Check whether password is correct
		$hash = hash('sha256', $salt.hash('sha256', $password1));
		if($jsonObject['admin']['records'][0][2] != $hash){
			$output = "Incorrect password.";
			echo $output;
			die();
		}
		else // Login attempt succeeded
		{
			validateUser($jsonObject['admin']['records'][0][1]); // Sets the session data for this user
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php">'; 
		}
	}
	else if($newUser)
	{
		// Retrieve user data via web service
		$response = call('GET','http://charlesmjenkins.com/reuse/webservice/api.php/admin?filter=email,eq,'.$email2.'&appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2');
		$jsonObject = json_decode($response,true);
		foreach($jsonObject['admin']['records'] as $item) {
		    $output = $jsonObject['admin']['records'][0][3];
		}
		
		// Check whether provided email is unique
		if($output !== ""){
			$output = "An account already exists with that email.";
			echo $output;
			die();
		}

		// Hash password and add account to database
		$hash = hash('sha256', $salt.hash('sha256', $password2));
		$data = "username=$username2&password=$hash&email=$email2";
		$response = call('POST','http://charlesmjenkins.com/reuse/webservice/api.php/admin?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2', $data);

		// Create account attempt succeeded
		validateUser($username2); // Sets the session data for this user
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