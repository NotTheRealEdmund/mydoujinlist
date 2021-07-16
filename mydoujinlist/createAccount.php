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

	// Check if the data from the register form was submitted, isset() will check if the data exists
	if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
		// Could not get the data that should have been sent.
		exit('Please fill the username, password, and email fields in the register page!');
	} else if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) { // For email validation
            	exit('Your email address is invalid');
        }

	// Prepare our SQL, preparing the SQL statement will prevent SQL injection
	if ($stmt = $conn->prepare('SELECT * FROM accounts WHERE username = ?')) {
		// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database
		$stmt->store_result();
		// Check if username entered already exists
		if ($stmt->num_rows > 0) {
			// Already existing username
			echo 'The username you entered already exists.';
		} else if ($stmt = $conn->prepare('SELECT * FROM accounts WHERE email = ?')) {
			// Bind parameters (s = string, i = int, b = blob, etc)
			$stmt->bind_param('s', $_POST['email']);
			$stmt->execute();
			// Store the result so we can check if the account exists in the database
			$stmt->store_result();
			// Check if email entered already exists
			if ($stmt->num_rows > 0) {
				// Already existing email
				echo 'The email you entered already exists.';
			} else {
				// Since neither username nor email already exists, we are ready to create a new user
				if ($stmt = $conn->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
					// Bind parameters (s = string, i = int, b = blob, etc)
					$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
					$stmt->bind_param('sss', $_POST['username'], $hashed_password, $_POST['email']);
					$stmt->execute();
					// Redirect to the login page
					header('Location: successful.html');
				}
			}
		}		
		// Close connection
		$stmt->close();
	}
?>
