<?php
	// We need to use sessions, so you should always start sessions using the below code
	session_start();

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

	// Check if the data from editUsername.php was submitted, isset() will check if the data exists
	if (!isset($_POST['username'])) {
		// Could not get the data that should have been sent.
		exit('Please fill in the new username field!');
	}

	// Prepare our SQL, preparing the SQL statement will prevent SQL injection
	if ($stmt = $conn->prepare('UPDATE accounts SET username = ? WHERE username = ?')) {
		// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
		$stmt->bind_param('ss', $_POST['username'], $_SESSION['name']);
		$stmt->execute();
		
		// Update session value so that changes will immediately show on the profile page
		$_SESSION['name'] = $_POST['username'];
		
		// Redirect to the profile page
		header('Location: profile.php');
	
		// Close connection
		$stmt->close();
	}
?>
