<!-- Some code from this source was used 
Author: David Adams
Date: 27 January 2021
Title: Secure Login System with PHP and MySQL
Available at: https://codeshack.io/secure-login-system-php-mysql/
-->

<?php
	// We need to use sessions, so you should always start sessions using the below code
	session_start();
	
	// If the user is not logged in, redirect to the login page
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.html');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Home Page</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="main.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
		<?php require 'navbar.html'; ?>
		
		<div class="content">
			<h2>Home Page</h2>
			
		</div>
	</body>
</html>
