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

	// Check if the data from editEmail.php was submitted, isset() will check if the data exists
	if (!isset($_POST['email'])) {
		// Could not get the data that should have been sent.
		exit('Please fill in the new email field!');
	} else if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) { // For email validation
            	exit('Your new email address is invalid');
        }

	// Prepare our SQL, preparing the SQL statement will prevent SQL injection
	if ($stmt = $conn->prepare('UPDATE accounts SET email = ? WHERE id = ?')) {  // We don't have the email info stored in sessions so instead we can use session id to update the correct entry
		// Bind parameters (s = string, i = int, b = blob, etc)
		$stmt->bind_param('si', $_POST['email'], $_SESSION['id']);
		$stmt->execute();
		
		// Redirect to the profile page
		header('Location: profile.php');
		
		// Close connection
		$stmt->close();
	}
?>
