<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: utilities.php
//
// Description: Assorted utility functions
// for use throughout web app.
// ---------------------------------------
// Acknowledgements:
// http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
// https://github.com/mevdschee/php-crud-api/blob/master/examples/client.php
	
	require "php_crud_api_transform.php";

	function cleanString($UserInput) {
        $UserInput = strip_tags($UserInput);
        $UserInput = str_replace("'", "", $UserInput);
        $UserInput = str_replace('"', "", $UserInput);
        $UserInput = htmlspecialchars($UserInput);
        return $UserInput;
    }
	function validateUser($username)
	{
		session_regenerate_id (); //this is a security measure

		$_SESSION['valid'] = 1;
		$_SESSION['username'] = $username;
	}
	function isLoggedIn()
	{
		if(isset($_SESSION['valid']) && $_SESSION['valid'])
			return true;
		else
			return false;
	}
	function logout()
	{
		$_SESSION = array(); //destroy all of the session variables
		session_destroy();
		
		header('Location: login.php');
	}
	function call($method, $url, $data = false) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Content-Length: ' . strlen($data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}
?>