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

	// Check if the data from the doujin list page was submitted, isset() will check if the data exists
	if (!isset($_POST['doujinNumber'])) {
		// Could not get the data that should have been sent.
		exit('Error obtaining doujin details!');
	}
	
	// Prepare our SQL, preparing the SQL statement will prevent SQL injection
	if ($stmt = $conn->prepare('SELECT * FROM selections WHERE user = ? AND doujinNumber = ?')) {  // First check if the user still has the doujin in doujin list
		// Bind parameters (s = string, i = int, b = blob, etc)
		$stmt->bind_param('si', $_SESSION['name'], $_POST['doujinNumber']);
		$stmt->execute();
		// Store the result so we can check if the user already entered the same doujin
		$stmt->store_result();
		
		// If the user no longer has that doujin in doujin list
		if ($stmt->num_rows <= 0) {
			exit('The doujin entry no longer exists in your list!');
		} else if ($stmt = $conn->prepare('DELETE FROM selections WHERE user = ? AND doujinNumber = ?')) {  // Otherwise, delete the doujin details from the doujins table in database
			// Bind parameters (s = string, i = int, b = blob, etc)
			$stmt->bind_param('si', $_SESSION['name'], $_POST['doujinNumber']);
			$stmt->execute();
			
			// Reset id of selections table in database such that the next entry will carry on where it left off
			$sql = "ALTER TABLE selections AUTO_INCREMENT = 1";
			$result = $conn->query($sql);
			
			// Redirect to the doujin list page
			header('Location: list.php');
		}
		
		// Close connection
		$stmt->close();
	}
?>
