<?php 
/// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: login.php
//
// Description: Entry page for users to
// either log into the admin portal or
// create an account.
// ---------------------------------------

	session_start(); 

	include "utilities.php";

	if(isLoggedIn())
	{
		header('Location: admin.php');
		die();
	}
?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Corvallis Sustainable Coalition: Reuse & Repair Directory Admin Login</title>
		
		<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
		
		<!-- Bootstrap CSS: https://getbootstrap.com/ -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
		<!-- Bootswatch Yeti Theme CSS: https://bootswatch.com/ -->
		<link rel="stylesheet" type="text/css" href="css/yeti.css">
		
		<!-- Signin CSS -->
		<link rel="stylesheet" type="text/css" href="css/signin.css">
		
		<!-- Tooltipster CSS: http://iamceege.github.io/tooltipster/ -->
		<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
		
		<!-- jQuery: https://jquery.com/ -->
		<script src="js/jquery.min.js"></script>
		
		<!-- jQuery Validation: http://jqueryvalidation.org/ -->
		<script src="js/jquery.validate.min.js"></script>
		
		<!-- Tooltipster JS: http://iamceege.github.io/tooltipster/ -->
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
		
		<script>
			// Tooltipster and Validation Setup
	        $(document).ready(function() {
	        	// Configure Tooltipster
			    $('input[type="text"], input[type="password"]').tooltipster({
			        trigger: 'custom',
			        onlyOne: false,
			        position: 'right'
			    });

				// Configure Valdation on login form
			    $('#loginForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            username1: {
			                required: true,
			                minlength: 2
			            },
			            password1: {
			                required: true,
			                minlength: 5
			            }
			        },
			        submitHandler: function (form) {
			        	$.ajax({
				            url: form.action,
				            type: form.method,
				            data: $(form).serialize(),
				            success: function(response) {
				                $('#status1').html(response);
				            }            
				        });
			        }
			    });

				// Configure Valdation on create account form
			    $('#createAccountForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            email: {
			                required: true,
			                email: true
			            },
			            username2: {
			                required: true,
			                minlength: 2
			            },
			            password2: {
			                required: true,
			                minlength: 5
			            },
			            password2Again: {
			                required: true,
			                minlength: 5,
			                equalTo: "#password2"
			            }
			        },
			        submitHandler: function (form) {
			            $.ajax({
				            url: form.action,
				            type: form.method,
				            data: $(form).serialize(),
				            success: function(response) {
				                $('#status2').html(response);
				            }            
				        });
			        }
			    });
	        });
    	</script>
	</head>

	<body>
		<div class="container">
		<div id="contentLogin">
			<form id="loginForm" method="POST" action="session.php" class="form-signin">
			   <img src="img/logox330w.jpg" alt="Corvallis Sustainable Coalition Logo"></span>
			   <h4 class="form-signin-heading subtitle">Reuse & Repair Admin Portal</h4>
			   <h2 class="form-signin-heading">Log in: </h2>
			   <input type="text" name="email1" id="email1" placeholder="Email" maxlength="50" class="form-control"/>

			   <input type="password" name="password1" id="password1" placeholder="Password" maxlength="100" class="form-control"/>

			   <input type="hidden" name="returningUser" value="true">
			   <input type="submit" value="Log In" class="btn btn-info loginBtn" />
			</form>

			<div id="status1"></div>

			<form id="createAccountForm" method="POST" action="session.php" class="form-signin">
			   <h2 class="form-signin-heading">Create an account: </h2>
			   <input type="text" name="email2" id="email2" placeholder="Email" maxlength="50" class="form-control" />

			   <input type="text" name="username2" id="username2" placeholder="Username" maxlength="20" class="form-control"/>

			   <input type="password" name="password2" id="password2" placeholder="Password" maxlength="100" class="form-control"/>

			   <input type="password" name="password2Again" id="password2Again" placeholder="Re-enter Password" maxlength="100" class="form-control"/>

			   <input type="hidden" name="newUser" value="true">
			   <input type="submit" value="Create Account" class="btn btn-info loginBtn" />
			</form>

			<div id="status2"></div>
		</div>
		</div>
	</body>
</html>