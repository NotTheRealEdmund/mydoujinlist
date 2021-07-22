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
	<style> 
		.grid-container { 
			display: grid;
			grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
			grid-gap: 10px;
			align-items: stretch;
			width: 1200px;
			margin: auto;
			padding-bottom: 150px;
		}
		.doujin {
			border: 1px solid #ccc;
			box-shadow: 2px 2px 6px 0px  rgba(0,0,0,0.3);
			height: 440px;
			border-radius: 10px;
			/*Required to make button stay at the bottom*/
			position: relative;
		}
		.doujin img {
		  	max-width: 100%;
		  	border-top-left-radius: 10px;
			border-top-right-radius: 10px;
		}
		.info {
		  	text-align: center;
		  	font-size: 14px;
		  	font-weight: 700;
		}
		.myButton {
		  	cursor: pointer;
			background: #e9658d;
			border: 0;
			color: white;
		  	padding: 10px;
		  	width: 100%;
		  	border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
		  	/*Required to make button stay at the bottom*/
		  	position: absolute;
		  	bottom: 0;
		}
	</style>
	<body class="loggedin">
		<?php require 'navbar.html'; ?>
		
		<?php
			// Database information	
			$DATABASE_HOST = 'localhost';
			$DATABASE_USER = 'root';
			$DATABASE_PASS = '';
			$DATABASE_NAME = 'mydoujinlist';
			
			// Connect to database
			$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
			// Exception handling
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			// Read from the doujins table in database
			$sql = "SELECT * FROM doujins";
			$result = $conn->query($sql);
			
			echo '<div class="content">';
			echo '<h2>Choose a doujin!</h2>';
			echo '</div>';
			
			// Show all entries in doujins table in database
			echo '<div class="grid-container">';
				while($row = $result->fetch_assoc()) {
					echo '<div class="doujin">';
					echo '<img src="' . $row["image_directory"] . '" width="250" height="350";">';
					echo '<div class="info">' . $row["title"] . ' [' . $row["artist"] . ']</div>';
					echo '<form method="post">';	// This form button does nothing for now
					echo '<input type="hidden" name="doujinNumber" value="' . $row['id'] . '">';
					echo '<input type="submit" value="Add to doujin list" class="myButton">';
					echo '</form>';
					echo '</div>';
				}
			echo '</div>';
		?>
	</body>
</html>
