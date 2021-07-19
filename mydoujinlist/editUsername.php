<?php
	// We need to use sessions, so you should always start sessions using the below code
	session_start();
	
	// If the user is not logged in redirect to the login page
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.html');
		exit;
	}
	
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

	// We don't have the password or email info stored in sessions so instead we can get the results from the database
	$stmt = $conn->prepare('SELECT password, email FROM accounts WHERE id = ?');
	// In this case we can use the account ID to get the account info
	$stmt->bind_param('i', $_SESSION['id']);
	$stmt->execute();
	$stmt->bind_result($password, $email);
	$stmt->fetch();
	$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Edit Username</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="main.css" rel="stylesheet" type="text/css">
		<style>
			.editButton {
				cursor: pointer;
				text-decoration: none;
				background: #e9658d;
				color: white;
			  	padding: 3px;
			  	border-radius: 5px;
			  	border: 0;
			}
		</style>
	</head>
	<body class="loggedin">
		<?php require 'navbar.html'; ?>
		
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Old username: <?=$_SESSION['name']?></p>
				<p>Enter your new username below:</p>
				<form action="changeUsername.php" method="post">
					<input type="text" name="username" placeholder="New Username" id="username" required>
					&nbsp;
				<input type="submit" value="Change" class="editButton">
			</form>
			</div>
		</div>
	</body>
</html>
