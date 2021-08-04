<!-- Some code from this source was used 
Author: David Adams
Date: 27 January 2021
Title: Secure Login System with PHP and MySQL
Available at: https://codeshack.io/secure-login-system-php-mysql/
--->

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
		<style> 
			::placeholder {
				font-size: 18px;
				color: #e9658d;
			}
			.mySearchButton {
				cursor: pointer;
				background: #e9658d;
				border: 0;
				color: white;
			  	padding: 3px;
			  	border-radius: 5px;
			}
			.grid-container { 
				display: grid;
				grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
				grid-gap: 10px;
				width: 1200px;
				margin: auto;
				padding-bottom: 150px;
			}
			button {
				padding: 0;
				border: 0;
				cursor: pointer;
			}
			.doujin {
				border: 1px solid #ccc;
				box-shadow: 2px 2px 6px 0px  rgba(0,0,0,0.3);
				height: auto;
				border-radius: 10px;
			}
			.doujin img {
			  	max-width: 100%;
			  	height: auto;
			  	border-top-left-radius: 10px;
				border-top-right-radius: 10px;
			}
			.info {
			  	text-align: center;
			  	font-size: 14px;
		  		font-weight: 700;
			}
		</style>
	</head>
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
			echo '<h2>';
			echo 'Choose a doujin!';
			// Add search bar to search doujins by title
			echo '<form method="post" style="float: right;">';
				echo '<input type="text" name="title_search" placeholder="Search your doujin!" required>';
				echo '&nbsp;&nbsp;';
				echo '<input type="submit" value="Enter" class="mySearchButton">';
			echo '</form>';
			echo '</h2>';
			echo '</div>';
			
			// If something was submitted through the title search bar
			if (isset($_POST["title_search"])) {
				// Find entries in doujins table in database where the title column contains input
				$title_search = $_POST["title_search"];
				$result = mysqli_query($conn, "SELECT * from doujins WHERE title LIKE '%$title_search%' OR artist LIKE '%$title_search%' OR tag LIKE '%$title_search%'"); 
			
				// If result is found
				if ($result->num_rows > 0) {
					echo '<div class="grid-container">';
						while($row = $result->fetch_assoc()) {
							echo '<form action="doujinDetails.php" method="post">';
							echo '<input type="hidden" name="doujinNumber" value="' . $row['id'] . '">';
							echo '<button type="submit">';
							echo '<div class="doujin">';
							echo '<img src="' . $row["image_directory"] . '">';
							echo '<div class="info">' . $row["title"] . ' [' . $row["artist"] . ']</div>';
							echo '</div>';
							echo '</button>';
							echo '</form>';
						}
					echo '</div>';
				}
				else {
					echo '<div style="text-align: center;">No results found!</div>';
				}
			}
			else {
				// When the page is just loaded
				// Show all entries in doujins table in database
				echo '<div class="grid-container">';
					while($row = $result->fetch_assoc()) {
						echo '<form action="doujinDetails.php" method="post">';
						echo '<input type="hidden" name="doujinNumber" value="' . $row['id'] . '">';
						echo '<button type="submit">';
						echo '<div class="doujin">';
						echo '<img src="' . $row["image_directory"] . '">';
						echo '<div class="info">' . $row["title"] . ' [' . $row["artist"] . ']</div>';
						echo '</div>';
						echo '</button>';
						echo '</form>';
					}
				echo '</div>';
			}
		?>
	</body>
</html>
