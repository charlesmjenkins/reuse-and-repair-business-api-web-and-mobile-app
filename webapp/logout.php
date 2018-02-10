<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: logout.php
//
// Description: Processes logout requests
// from admin portal.
// ---------------------------------------
// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/

	include "utilities.php";
	
	session_start(); 
	
	// If the user has not logged in
	if(!isLoggedIn())
	{
		header('Location: login.php');
		die();
	}
	
	logout();
?>