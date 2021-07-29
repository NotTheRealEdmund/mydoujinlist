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

	// Check if the data from the score page was submitted, isset() will check if the data exists
	if (!isset($_POST['score'], $_POST['doujinNumber'], $_POST['review'])) {
		// Could not get the data that should have been sent.
		exit('Error obtaining doujin details!');
	}
	
	// Since user reviews are optional, check if review is empty
	if (empty($_POST['review'])) {
		$review = null;
	} else {
		$review = $_POST['review'];
	}
	
	// Prepare our SQL, preparing the SQL statement will prevent SQL injection
	if ($stmt = $conn->prepare('SELECT * FROM selections WHERE user = ? AND doujinNumber = ?')) {  // Check if the user already entered the same doujin
		// Bind parameters (s = string, i = int, b = blob, etc)
		$stmt->bind_param('si', $_SESSION['name'], $_POST['doujinNumber']);
		$stmt->execute();
		// Store the result so we can check if the user already entered the same doujin
		$stmt->store_result();
		
		// If the user already entered the same doujin
		if ($stmt->num_rows > 0) {
			exit('The doujin entry already exists in your list!');
		} else if ($stmt = $conn->prepare('INSERT INTO selections (user, doujinNumber, score, review) VALUES (?, ?, ?, ?)')) {  // Otherwise, enter the doujin details into doujins table in database
			// Bind parameters (s = string, i = int, b = blob, etc)
			$stmt->bind_param('siis', $_SESSION['name'], $_POST['doujinNumber'], $_POST['score'], $review);
			$stmt->execute();

			// Redirect to the doujin list page
			header('Location: list.php');
		}
		
		// Close connection
		$stmt->close();
	}
?>
